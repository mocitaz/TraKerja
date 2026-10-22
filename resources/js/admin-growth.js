export function adminGrowth(config) {
    return {
        labels: config.labels,
        counts: config.counts,
        period: 14,
        selected: 13,
        tooltipOpen: false,
        get values() { return this.counts.slice(-this.period); },
        get dates() { return this.labels.slice(-this.period); },
        get total() { return this.values.reduce((sum, value) => sum + value, 0); },
        get previousTotal() { return this.counts.slice(-this.period * 2, -this.period).reduce((sum, value) => sum + value, 0); },
        get change() { return this.previousTotal ? Math.round((this.total - this.previousTotal) / this.previousTotal * 100) : null; },
        get peak() { return Math.max(...this.values, 0); },
        get peakIndex() { return this.values.indexOf(this.peak); },
        get average() { return (this.total / this.period).toLocaleString(config.locale, { maximumFractionDigits: 1 }); },
        get max() { return Math.max(2, Math.ceil(this.peak / 2) * 2); },
        get ticks() { return [this.max, this.max / 2, 0]; },
        get points() { return this.values.map((value, index) => ({ x: this.x(index), y: this.y(value) })); },
        get line() {
            return this.points.map((point, index, points) => {
                if (!index) return `M${point.x},${point.y}`;
                const previous = points[index - 1];
                const middle = (previous.x + point.x) / 2;
                return `C${middle},${previous.y} ${middle},${point.y} ${point.x},${point.y}`;
            }).join(' ');
        },
        get area() { return `${this.line} L608,212 L16,212 Z`; },
        get axisIndices() { return [...new Set([0, Math.round((this.period - 1) / 4), Math.round((this.period - 1) / 2), Math.round((this.period - 1) * 3 / 4), this.period - 1])]; },
        get tooltipLeft() { return Math.max(15, Math.min(85, this.x(this.selected) / 624 * 100)); },
        x(index) { return 16 + index * 592 / (this.period - 1); },
        y(value) { return 212 - value / this.max * 176; },
        setPeriod(days) {
            if (![7, 14, 30].includes(days)) return;
            this.period = days;
            this.selected = days - 1;
            this.tooltipOpen = false;
        },
        inspect(event) {
            const bounds = event.currentTarget.getBoundingClientRect();
            const position = (event.clientX - bounds.left) / bounds.width * 624;
            this.selected = Math.max(0, Math.min(this.period - 1, Math.round((position - 16) / 592 * (this.period - 1))));
            this.tooltipOpen = true;
        },
        step(direction) {
            this.selected = Math.max(0, Math.min(this.period - 1, this.selected + direction));
            this.tooltipOpen = true;
        },
    };
}

if (typeof window !== 'undefined') window.adminGrowth = adminGrowth;
