<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserExperience;
use App\Models\UserSkill;
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
 
            // Normalize and split text into clean lines
            $text = str_replace("\r", "", $text);
            $lines = explode("\n", $text);
            $lines = array_map('trim', $lines);
            $lines = array_filter($lines);
 
            $experiences = [];
            $skills = [];
            $currentSection = null;
            
            // Comprehensive job titles dictionary
            $jobTitlesRegex = '/\b(Software Engineer|Developer|Web Developer|Frontend|Backend|Fullstack|Designer|UX|UI|Product Manager|Project Manager|Data Analyst|Data Scientist|DevOps|System Administrator|QA Engineer|Marketing|Sales|Writer|Content Creator|Accountant|HR|Consultant|Architect|Intern|Engineer|Lead|Specialist|Manager|Director|Head)\b/i';
 
            foreach ($lines as $line) {
                if (empty($line) || strlen($line) < 3) continue;
                $upperLine = strtoupper($line);
                
                // Section transitions detection
                if (preg_match('/\b(EXPERIENCE|WORK|EMPLOYMENT|HISTORY|PEKERJAAN|KARIR|PENGALAMAN)\b/i', $line)) {
                    $currentSection = 'experience';
                    continue;
                } elseif (preg_match('/\b(EDUCATION|PENDIDIKAN|AKADEMIS|UNIVERSITY|KULIAH|COLLEGE)\b/i', $line)) {
                    $currentSection = 'education';
                    continue;
                } elseif (preg_match('/\b(SKILLS|KEAHLIAN|COMPETENC|KETERAMPILAN|TECHNOLOGIES|KEMAMPUAN)\b/i', $line)) {
                    $currentSection = 'skills';
                    continue;
                }
 
                if ($currentSection === 'experience') {
                    if (preg_match($jobTitlesRegex, $line)) {
                        $position = $line;
                        $company = 'Company Name';
                        
                        if (preg_match('/(.*)\s+(?:at|@|in)\s+(.*)/i', $line, $matches)) {
                            $position = trim($matches[1]);
                            $company = trim($matches[2]);
                        } elseif (str_contains($line, ' - ')) {
                            $parts = explode(' - ', $line);
                            $position = trim($parts[0]);
                            $company = trim($parts[1]);
                        } elseif (str_contains($line, ' | ')) {
                            $parts = explode(' | ', $line);
                            $position = trim($parts[0]);
                            $company = trim($parts[1]);
                        }
 
                        $experiences[] = [
                            'position' => substr($position, 0, 100),
                            'company_name' => substr($company, 0, 100),
                            'location' => 'Indonesia',
                            'start_date' => now()->subYear()->format('Y-m-d'),
                            'is_current' => count($experiences) === 0,
                            'description' => 'Parsed from CV: ' . $line,
                        ];
                    }
                } elseif ($currentSection === 'skills') {
                    if (str_contains($line, ',')) {
                        $parts = explode(',', $line);
                        foreach ($parts as $part) {
                            $skillName = trim($part);
                            if (strlen($skillName) >= 2 && strlen($skillName) <= 30 && !preg_match('/(email|phone|website|address|contact)/i', $skillName)) {
                                $skills[] = $skillName;
                            }
                        }
                    } else {
                        if (strlen($line) >= 2 && strlen($line) <= 30 && !preg_match('/(email|phone|website|address|contact)/i', $line)) {
                            $skills[] = $line;
                        }
                    }
                }
            }
 
            // Fallback experience if no section matched
            if (empty($experiences)) {
                foreach ($lines as $line) {
                    if (preg_match($jobTitlesRegex, $line)) {
                        $position = $line;
                        $company = 'Company Name';
                        if (preg_match('/(.*)\s+(?:at|@|in)\s+(.*)/i', $line, $matches)) {
                            $position = trim($matches[1]);
                            $company = trim($matches[2]);
                        }
                        $experiences[] = [
                            'position' => substr($position, 0, 100),
                            'company_name' => substr($company, 0, 100),
                            'location' => 'Indonesia',
                            'start_date' => now()->subYears(2)->format('Y-m-d'),
                            'is_current' => true,
                            'description' => 'Parsed from CV: ' . $line,
                        ];
                        break;
                    }
                }
            }
 
            // Persist Extracted Experiences
            foreach ($experiences as $index => $exp) {
                UserExperience::create([
                    'user_id' => $user->id,
                    'position' => $exp['position'],
                    'company_name' => $exp['company_name'],
                    'location' => $exp['location'],
                    'start_date' => $exp['start_date'],
                    'is_current' => $exp['is_current'],
                    'description' => $exp['description'],
                    'display_order' => $index + 1
                ]);
            }
 
            // Persist Extracted Skills
            $skills = array_unique($skills);
            foreach (array_slice($skills, 0, 8) as $index => $skillName) {
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
