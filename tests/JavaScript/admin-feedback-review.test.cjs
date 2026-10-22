const test = require('node:test');
const assert = require('node:assert/strict');

test('closing delete review resets confirmation, while a submitting form stays open', async () => {
    global.window = {};
    await import('../../resources/js/admin-feedback-review.js');
    const vm = window.adminFeedbackReview(true);
    assert.equal(vm.open, true);
    vm.deleting = true; vm.close();
    assert.equal(vm.open, false); assert.equal(vm.deleting, false);
    vm.review(); vm.busy = true; vm.close();
    assert.equal(vm.open, true);
    vm.busy = false; vm.close(); assert.equal(vm.open, false);
});
