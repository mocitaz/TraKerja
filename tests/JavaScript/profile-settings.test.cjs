const test = require('node:test');
const assert = require('node:assert/strict');
async function workspace() {
    global.window={innerWidth:1440,location:{href:'http://localhost/profile?tab=resumes&page=2'},history:{replaceState(a,b,url){window.location.href=url.toString();}}};
    const {profileSettings}=await import('../../resources/js/profile-settings.js');
    return profileSettings({tabs:['about','resumes','security'],tab:'about',avatar:'amber'});
}
test('profile tabs reject unavailable sections and preserve unrelated query parameters',async()=>{
    const vm=await workspace();vm.selectTab('security');
    assert.equal(new URL(window.location.href).searchParams.get('tab'),'security');
    assert.equal(new URL(window.location.href).searchParams.get('page'),'2');
    vm.selectTab('membership');assert.equal(vm.tab,'security');
    vm.selectTab('about');assert.equal(new URL(window.location.href).searchParams.has('tab'),false);
});
test('dialog records are copied and unknown dialog names do not replace the active review',async()=>{
    const vm=await workspace();const record={id:7,title:"Candidate's CV"};
    vm.openDialog('resume-rename',record);vm.record.title='New label';
    assert.equal(record.title,"Candidate's CV");
    vm.openDialog('invalid');assert.equal(vm.dialog,'resume-rename');
    vm.busy=true;vm.closeDialog();assert.equal(vm.dialog,'resume-rename');
    vm.busy=false;vm.closeDialog();assert.equal(vm.dialog,null);
});
test('temporary photo previews are released on replacement and closing',async()=>{
    const vm=await workspace();const released=[];
    const originalCreate=URL.createObjectURL, originalRevoke=URL.revokeObjectURL;
    URL.createObjectURL=file=>'blob:'+file.name;URL.revokeObjectURL=url=>released.push(url);
    try {
        vm.$refs={photoInput:{value:'file'}};
        vm.previewPhoto({name:'one.png'});vm.previewPhoto({name:'two.png'});
        assert.deepEqual(released,['blob:one.png']);assert.equal(vm.photoName,'two.png');
        vm.closeDialog();assert.deepEqual(released,['blob:one.png','blob:two.png']);
        assert.equal(vm.photoUrl,'');assert.equal(vm.$refs.photoInput.value,'');
    } finally {URL.createObjectURL=originalCreate;URL.revokeObjectURL=originalRevoke;}
});
