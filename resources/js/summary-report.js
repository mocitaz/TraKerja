export function summaryRecordTable(rows) {
    return {
        rows, query: '', sort: 'total', page: 1, pageSize: 6,
        init() {
            this.$watch('query', () => { this.page = 1; });
            this.$watch('sort', () => { this.page = 1; });
        },
        get filteredRows() {
            const query = this.query.trim().toLocaleLowerCase();
            return this.rows.filter(row => row.name.toLocaleLowerCase().includes(query)).sort((a, b) => {
                if (this.sort === 'name') return a.name.localeCompare(b.name);
                return Number(b[this.sort]) - Number(a[this.sort]) || a.name.localeCompare(b.name);
            });
        },
        get pageCount() { return Math.max(1, Math.ceil(this.filteredRows.length / this.pageSize)); },
        get visibleRows() { return this.filteredRows.slice((this.page - 1) * this.pageSize, this.page * this.pageSize); },
        get firstRow() { return this.filteredRows.length ? (this.page - 1) * this.pageSize + 1 : 0; },
        get lastRow() { return Math.min(this.page * this.pageSize, this.filteredRows.length); },
        initials(name) {
            const words = name.trim().replace(/^(?:(?:PT|CV)\.?\s+)+/iu, '').split(/\s+/u).filter(Boolean);
            return (words.length > 1 ? words.slice(0, 2).map(word => Array.from(word)[0]).join('') : Array.from(words[0] || name).slice(0, 2).join('')).toLocaleUpperCase();
        },
    };
}

if (typeof window !== 'undefined') window.summaryRecordTable = summaryRecordTable;
