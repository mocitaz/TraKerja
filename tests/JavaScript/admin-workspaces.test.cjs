const test = require('node:test');
const assert = require('node:assert/strict');
global.window = { location: { href: 'http://localhost/admin', reload() {} } };
async function factory(file, name) {
    await import('../../' + file);
    return window[name];
}
test('email preview only requests the preview and audience endpoints', async () => {
    const workspace = (await factory('resources/js/admin-email-workspace.js', 'emailBlastWorkspace'))({previewUrl:'preview',initUrl:'audience',sendUrl:'send',logUrl:'log'});
    workspace.$refs = { composer: { reportValidity:()=>true }, previewFrame: {} };
    workspace.step = 5; workspace.language = 'en'; workspace.bannerChoice = 'no';
    const requests=[];
    workspace.request=async(url)=>{requests.push(url);return url==='preview' ? '<p>Hello</p>' : {users:[{id:1,name:'Member',email:'member@example.test'}]};};
    await workspace.preview();
    assert.deepEqual(requests,['preview','audience']);
    assert.equal(workspace.previewReady,true);
    assert.equal(workspace.$refs.previewFrame.srcdoc,'<p>Hello</p>');
    await workspace.send();
    assert.deepEqual(requests,['preview','audience']);
});
test('failed delivery records failure instead of incrementing sent count', async () => {
    const workspace=(await factory('resources/js/admin-email-workspace.js','emailBlastWorkspace'))({sendUrl:'send',logUrl:'log'});
    workspace.modal='confirm';workspace.reviewConfirmed=true;workspace.previewReady=true;workspace.users=[{id:1,name:'Member',email:'member@example.test'}];workspace.payload={email_type:'welcome',target_user:'all'};
    let saved;
    workspace.request=async(url,payload)=>{if(url==='send')return{success:false,error:'Delivery rejected'};saved=payload;return{success:true};};
    await workspace.send();
    assert.equal(workspace.processed,1);assert.equal(workspace.successCount,0);assert.equal(workspace.failures.length,1);assert.equal(saved.failed_count,1);assert.equal(saved.success_count,0);assert.equal(workspace.sending,false);
});
test('payment cancellation cannot start without confirmation', async () => {
    const workspace=(await factory('resources/js/admin-payments-workspace.js','adminPaymentsWorkspace'))();
    workspace.payment={id:1,status:'PENDING'};
    await workspace.cancel();
    assert.equal(workspace.busy,false);assert.equal(workspace.error,'');
    assert.match(workspace.date('2026-10-10T05:00:00Z'),/12[.:]00/);
    assert.match(workspace.date('2026-10-10T05:00:00Z'),/WIB$/);
});
