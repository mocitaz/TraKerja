import test from 'node:test';
import assert from 'node:assert/strict';
import {autofillJob} from '../../resources/js/job-autofill.js';
const payload={success:true,job_title:'Engineer',company_name:'Example',location:'Jakarta',career_level:'Contract',platform:'JobStreet',description:'Build APIs\nRequirements: SQL',partial:false};
function setup(fetcher){const state={company_name:'',position:'',location:'',career_level:'',platform:'',notes:''};return {state,options:{url:'https://id.jobstreet.com/job/123',read:f=>state[f],write:async(f,v)=>{state[f]=v;},csrf:'fixture',fetcher}};}
test('autofill maps every available field including employment type and description',async()=>{
 let sent;const {state,options}=setup(async(url,opts)=>{sent={url,opts};return {ok:true,json:async()=>payload};});
 const result=await autofillJob(options);assert.equal(state.company_name,'Example');assert.equal(state.career_level,'Contract');assert.match(state.notes,/Requirements: SQL/);assert.equal(result.filled,6);assert.equal(sent.url,'/jobs/scrape-url');assert.equal(sent.opts.headers['X-CSRF-TOKEN'],'fixture');
});
test('manual content and edits during the request are preserved',async()=>{
 const fixture=setup(async()=>{fixture.state.position='Typed while loading';return {ok:true,json:async()=>payload};});fixture.state.company_name='My company';fixture.state.notes='My notes';await autofillJob(fixture.options);assert.equal(fixture.state.position,'Typed while loading');assert.equal(fixture.state.company_name,'My company');assert.equal(fixture.state.notes,'My notes');
});
test('late response for another URL never changes the form',async()=>{
 const {state,options}=setup(async()=>({ok:true,json:async()=>payload}));options.isCurrent=()=>false;const r=await autofillJob(options);assert.equal(r.stale,true);assert.equal(state.company_name,'');
});
test('blocked page preserves form and never invokes a public proxy',async()=>{
 let calls=0;const {state,options}=setup(async(url)=>{calls++;assert.equal(url,'/jobs/scrape-url');return {ok:false,json:async()=>({message:'Portal blocked access'})};});await assert.rejects(()=>autofillJob(options),/Portal blocked access/);assert.equal(calls,1);assert.equal(state.company_name,'');
});
