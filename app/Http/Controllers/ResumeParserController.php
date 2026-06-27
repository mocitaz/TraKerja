<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserExperience;
use App\Models\UserSkill;
use App\Models\UserEducation;
use App\Models\UserProject;
use Smalot\PdfParser\Parser;
 
class ResumeParserController extends Controller
{
    public function importPdf(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|max:10240',
        ]);

        $file = $request->file('resume');
        if (strtolower($file->getClientOriginalExtension()) !== 'pdf') {
            return response()->json([
                'success' => false,
                'message' => 'Format file harus berupa PDF.',
            ], 422);
        }

        $user = Auth::user();
 
        try {
            $path = $request->file('resume')->getRealPath();
            
            // Initialize Smalot PDF Parser
            $parser = new Parser();
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();
 
            // Strip zero-width characters and normalize text
            $text = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text);
            $text = str_replace("\r", "", $text);
            $lines = explode("\n", $text);
            $lines = array_map('trim', $lines);
            $lines = array_values(array_filter($lines));
 
            $experiences = [];
            $projects = [];
            $education = [];
            $skills = [];
            $currentSection = null;
            
            $dateRangeRegex = '/\b(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\s+\d{4}\s*-\s*(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|Present)[a-z]*\s*(\d{4})?/i';
 
            // Pre-identify company names to prevent them from being appended to descriptions
            $companyIndices = [];
            for ($i = 0; $i < count($lines); $i++) {
                if (isset($lines[$i]) && preg_match($dateRangeRegex, $lines[$i])) {
                    if ($i > 0) {
                        $companyIndices[$i - 1] = true;
                    }
                }
            }
 
            $tempCompany = '';
            $tempPosition = '';
            $tempStart = '';
            $tempEnd = '';
            $tempCurrent = false;
            $tempBullets = [];
 
            for ($i = 0; $i < count($lines); $i++) {
                $line = $lines[$i];
                if (empty($line)) continue;
                $upperLine = strtoupper($line);
                
                // Section transitions detection
                if ($upperLine === 'WORK EXPERIENCES' || $upperLine === 'WORK EXPERIENCE' || $upperLine === 'EXPERIENCE') {
                    if ($tempPosition) {
                        $experiences[] = [
                            'company_name' => $tempCompany ?: 'Company',
                            'position' => $tempPosition,
                            'start_date' => $tempStart,
                            'end_date' => $tempEnd,
                            'is_current' => $tempCurrent,
                            'description' => implode("\n", $tempBullets)
                        ];
                        $tempPosition = '';
                        $tempBullets = [];
                    }
                    $currentSection = 'experience';
                    continue;
                } elseif ($upperLine === 'PROJECT EXPERIENCES' || $upperLine === 'PROJECTS' || $upperLine === 'PROJECT EXPERIENCE') {
                    if ($tempPosition) {
                        $experiences[] = [
                            'company_name' => $tempCompany ?: 'Company',
                            'position' => $tempPosition,
                            'start_date' => $tempStart,
                            'end_date' => $tempEnd,
                            'is_current' => $tempCurrent,
                            'description' => implode("\n", $tempBullets)
                        ];
                        $tempPosition = '';
                        $tempBullets = [];
                    }
                    $currentSection = 'projects';
                    continue;
                } elseif ($upperLine === 'EDUCATION' || $upperLine === 'EDUCATIONS') {
                    if ($tempPosition) {
                        $experiences[] = [
                            'company_name' => $tempCompany ?: 'Company',
                            'position' => $tempPosition,
                            'start_date' => $tempStart,
                            'end_date' => $tempEnd,
                            'is_current' => $tempCurrent,
                            'description' => implode("\n", $tempBullets)
                        ];
                        $tempPosition = '';
                        $tempBullets = [];
                    }
                    $currentSection = 'education';
                    continue;
                } elseif ($upperLine === 'ADDITIONAL' || $upperLine === 'SKILLS' || $upperLine === 'SKILL') {
                    if ($tempPosition) {
                        $experiences[] = [
                            'company_name' => $tempCompany ?: 'Company',
                            'position' => $tempPosition,
                            'start_date' => $tempStart,
                            'end_date' => $tempEnd,
                            'is_current' => $tempCurrent,
                            'description' => implode("\n", $tempBullets)
                        ];
                        $tempPosition = '';
                        $tempBullets = [];
                    }
                    $currentSection = 'skills';
                    continue;
                }
 
                if ($currentSection === 'experience') {
                    if (preg_match($dateRangeRegex, $line, $matches)) {
                        if ($tempPosition) {
                            $experiences[] = [
                                'company_name' => $tempCompany ?: 'Company',
                                'position' => $tempPosition,
                                'start_date' => $tempStart,
                                'end_date' => $tempEnd,
                                'is_current' => $tempCurrent,
                                'description' => implode("\n", $tempBullets)
                            ];
                            $tempBullets = [];
                        }
                        
                        $dateRange = $matches[0];
                        $posPart = trim(str_replace($dateRange, '', $line));
                        $tempPosition = $posPart ?: 'Software Engineer';
                        $tempCompany = isset($lines[$i - 1]) ? $lines[$i - 1] : 'Company';
                        
                        $dates = explode('-', $dateRange);
                        $startStr = trim($dates[0]);
                        $endStr = isset($dates[1]) ? trim($dates[1]) : 'Present';
                        
                        $tempStart = date('Y-m-d', strtotime($startStr));
                        if (strtolower($endStr) === 'present') {
                            $tempEnd = null;
                            $tempCurrent = true;
                        } else {
                            $tempEnd = date('Y-m-d', strtotime($endStr));
                            $tempCurrent = false;
                        }
                    } elseif (preg_match('/^[●○•\-]/u', $line)) {
                        $cleanBullet = preg_replace('/^[●○•\-]\s*/u', '', $line);
                        $tempBullets[] = '- ' . $cleanBullet;
                    } else {
                        if (isset($companyIndices[$i])) {
                            continue;
                        }
                        if (!empty($tempBullets)) {
                            $lastIndex = count($tempBullets) - 1;
                            $tempBullets[$lastIndex] .= "\n" . $line;
                        }
                    }
                } elseif ($currentSection === 'projects') {
                    if (preg_match('/^[●○•\-]/u', $line)) {
                        if (!empty($projects)) {
                            $lastProjectIndex = count($projects) - 1;
                            $cleanBullet = preg_replace('/^[●○•\-]\s*/u', '', $line);
                            $projects[$lastProjectIndex]['description'][] = '- ' . $cleanBullet;
                        }
                    } else {
                        $isNewProject = false;
                        if (preg_match('/^[a-z]/', $line)) {
                            $isNewProject = false;
                        } elseif (empty($projects)) {
                            $isNewProject = true;
                        } else {
                            $lastProj = $projects[count($projects) - 1];
                            if (!empty($lastProj['description'])) {
                                $nextLine = '';
                                for ($j = $i + 1; $j < count($lines); $j++) {
                                    if (isset($lines[$j]) && !empty($lines[$j])) {
                                        $nextLine = $lines[$j];
                                        break;
                                    }
                                }
                                if (preg_match('/^[●○•\-]/u', $nextLine)) {
                                    $isNewProject = true;
                                }
                            }
                        }
                        
                        if ($isNewProject) {
                            $projects[] = [
                                'name' => $line,
                                'description' => []
                            ];
                        } else {
                            if (!empty($projects)) {
                                $lastProjIdx = count($projects) - 1;
                                if (!empty($projects[$lastProjIdx]['description'])) {
                                    $lastBulletIdx = count($projects[$lastProjIdx]['description']) - 1;
                                    $projects[$lastProjIdx]['description'][$lastBulletIdx] .= "\n" . $line;
                                } else {
                                    $projects[$lastProjIdx]['name'] .= ' ' . $line;
                                }
                            }
                        }
                    }
                } elseif ($currentSection === 'education') {
                    if (str_contains($line, 'University') || str_contains($line, 'Institute') || str_contains($line, 'School') || str_contains($upperLine, 'TELKOM')) {
                        $degree = '';
                        if (isset($lines[$i + 1])) {
                            $degree = $lines[$i + 1];
                        }
                        $year = '';
                        if (isset($lines[$i + 2])) {
                            $year = $lines[$i + 2];
                        }
                        
                        $education[] = [
                            'institution' => $line,
                            'degree' => $degree,
                            'year' => $year
                        ];
                        $i += 2;
                    }
                } elseif ($currentSection === 'skills') {
                    $cleanLine = preg_replace('/^[●○•\-\s○]/u', '', $line);
                    if (str_contains($cleanLine, ':')) {
                        $parts = explode(':', $cleanLine);
                        $cleanLine = isset($parts[1]) ? trim($parts[1]) : $cleanLine;
                    }
                    
                    if (str_contains($cleanLine, ',')) {
                        $parts = explode(',', $cleanLine);
                        foreach ($parts as $p) {
                            $skill = trim($p);
                            if (strlen($skill) > 1 && strlen($skill) < 40 && !preg_match('/(email|phone|website|address|contact|Technical Skills)/i', $skill)) {
                                $skills[] = $skill;
                            }
                        }
                    } else {
                        $skill = trim($cleanLine);
                        if (strlen($skill) > 1 && strlen($skill) < 40 && !preg_match('/(email|phone|website|address|contact|Technical Skills)/i', $skill)) {
                            $skills[] = $skill;
                        }
                    }
                }
            }
 
            // Commit final work experience if exists
            if ($tempPosition) {
                $experiences[] = [
                    'company_name' => $tempCompany ?: 'Company',
                    'position' => $tempPosition,
                    'start_date' => $tempStart,
                    'end_date' => $tempEnd,
                    'is_current' => $tempCurrent,
                    'description' => implode("\n", $tempBullets)
                ];
            }
 
            // Purge existing data to keep CV Builder fresh and clean
            $user->experiences()->delete();
            $user->educations()->delete();
            $user->projects()->delete();
            $user->skills()->delete();
 
            // Save parsed Experiences
            foreach ($experiences as $index => $exp) {
                UserExperience::create([
                    'user_id' => $user->id,
                    'position' => substr($exp['position'], 0, 100),
                    'company_name' => substr($exp['company_name'], 0, 100),
                    'location' => 'Indonesia',
                    'start_date' => $exp['start_date'],
                    'end_date' => $exp['end_date'],
                    'is_current' => $exp['is_current'],
                    'description' => $exp['description'],
                    'display_order' => $index + 1
                ]);
            }
 
            // Save parsed Projects
            foreach ($projects as $index => $proj) {
                $cleanedName = $proj['name'];
                if (str_contains($cleanedName, ' | ')) {
                    $parts = explode(' | ', $cleanedName);
                    $cleanedName = trim($parts[0]);
                }
                
                UserProject::create([
                    'user_id' => $user->id,
                    'project_name' => substr($cleanedName, 0, 100),
                    'role' => 'Lead Developer',
                    'start_date' => now()->subMonths(6)->format('Y-m-d'),
                    'is_ongoing' => true,
                    'description' => implode("\n", $proj['description']),
                    'display_order' => $index + 1
                ]);
            }
 
            // Save parsed Educations
            foreach ($education as $index => $edu) {
                $startEduDate = null;
                $endEduDate = null;
                $isEduCurrent = false;
                
                if (preg_match('/(\d{4})\s*-\s*(\d{4}|Present)/i', $edu['year'], $eduYearMatches)) {
                    $startEduDate = $eduYearMatches[1] . '-01-01';
                    if (strtolower($eduYearMatches[2]) === 'present') {
                        $isEduCurrent = true;
                    } else {
                        $endEduDate = $eduYearMatches[2] . '-12-31';
                    }
                } else {
                    $startEduDate = now()->subYears(4)->format('Y-01-01');
                    $endEduDate = now()->format('Y-12-31');
                }
 
                UserEducation::create([
                    'user_id' => $user->id,
                    'institution_name' => substr($edu['institution'], 0, 100),
                    'degree' => substr($edu['degree'], 0, 100) ?: 'Bachelor',
                    'major' => 'Computer Science',
                    'start_date' => $startEduDate,
                    'end_date' => $endEduDate,
                    'is_current' => $isEduCurrent,
                    'display_order' => $index + 1
                ]);
            }
 
            // Save parsed Skills
            $skills = array_unique($skills);
            foreach (array_slice($skills, 0, 12) as $index => $skillName) {
                UserSkill::create([
                    'user_id' => $user->id,
                    'skill_name' => substr($skillName, 0, 50),
                    'category' => 'Technical',
                    'proficiency_level' => 'Intermediate',
                    'years_of_experience' => 1,
                    'display_order' => $index + 1
                ]);
            }
 
            return response()->json([
                'success' => true,
                'message' => 'CV PDF berhasil diproses dan di-impor ke dalam CV Builder!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca file PDF: ' . $e->getMessage(),
            ], 500);
        }
    }
}
