export function analyticsChart(points) {
    return {
        points, selected: Math.max(0, points.length - 1), hovered: null,
        applicationsVisible: true, membersVisible: true,
        get current() { return this.points[this.hovered ?? this.selected]; },
        select(index) { this.selected = Math.max(0, Math.min(this.points.length - 1, Number(index))); },
    };
}
export function analyticsWorkspace() {
    return {
        tab: 'overview',
        selectTab(tab) {
            if (['overview', 'usage', 'community'].includes(tab)) this.tab = tab;
        },
        moveTab(direction) {
            const tabs = ['overview', 'usage', 'community'];
            this.selectTab(tabs[(tabs.indexOf(this.tab) + direction + tabs.length) % tabs.length]);
            this.$nextTick(() => document.getElementById('analytics-tab-' + this.tab)?.focus());
        },
    };
}
if (typeof window !== 'undefined') {
    window.adminAnalyticsChart = analyticsChart;
    window.adminAnalyticsWorkspace = analyticsWorkspace;
}
