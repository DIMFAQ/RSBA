<div class="flex flex-col gap-4" x-data="{
    chart: null,
    nodes: @js($nodes),
    initChart() {
        if (!this.nodes || this.nodes.length === 0) return;
        
        const container = this.$refs.chartContainer;
        if (!container) return;
        container.innerHTML = '';

        this.chart = new d3.OrgChart()
            .container(container)
            .data(this.nodes)
            .nodeWidth(d => 260)
            .nodeHeight(d => 135)
            .childrenMargin(d => 45)
            .compactMarginBetween(d => 30)
            .compactMarginPair(d => 40)
            .neighbourMargin((a, b) => 25)
            .nodeContent(function(d, i, arr, state) {
                const isRoot = !d.data.parentId;
                const hasEmp = d.data.hasEmployee;
                const empName = d.data.employeeName;
                const title = d.data.title;
                const name = d.data.name;
                const dept = d.data.department;

                const headerBg = isRoot ? 'bg-indigo-600 text-white' : 'bg-slate-700 text-slate-100';
                const empBadgeClass = hasEmp ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200';
                const empIcon = hasEmp ? 'tabler.user-check' : 'tabler.alert-triangle';

                return `
                    <div class="flex h-full w-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-md transition-all hover:shadow-lg">
                        <div class="flex items-center justify-between px-3 py-2 ${headerBg}">
                            <span class="text-xs font-semibold uppercase tracking-wider">${dept}</span>
                            <span class="rounded bg-white/20 px-1.5 py-0.5 text-[10px] font-bold text-white">${title}</span>
                        </div>
                        <div class="flex flex-1 flex-col justify-between p-3">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 line-clamp-1">${name}</h4>
                            </div>
                            <div class="mt-2 flex items-center gap-1.5 rounded-lg border px-2 py-1 text-xs font-medium ${empBadgeClass}">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="truncate">${empName}</span>
                            </div>
                        </div>
                    </div>
                `;
            })
            .render();
    }
}" x-init="$nextTick(() => initChart())" x-effect="nodes = @js($nodes); $nextTick(() => initChart())">

    {{-- D3 & D3-Org-Chart Scripts --}}
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-org-chart@3.1.0"></script>

    {{-- Header & Action Controls --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg bg-white p-4 shadow-sm">
        <div class="flex items-center gap-2">
            <h3 class="text-base font-semibold text-slate-800">Bagan Struktur Organisasi RSBA</h3>
            <span class="rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">D3 Org Chart</span>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Filter Departemen --}}
            <div class="w-48">
                <x-ts:select.styled
                    wire:model.live="bagian_id"
                    :options="$bagianOptions"
                    select="label:label|value:value"
                    placeholder="Semua Departemen..."
                />
            </div>

            <div class="flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-50 p-1">
                <x-ts:button sm outline color="slate" icon="tabler.zoom-in" @click="chart?.zoomIn()" tooltip="Zoom In" />
                <x-ts:button sm outline color="slate" icon="tabler.zoom-out" @click="chart?.zoomOut()" tooltip="Zoom Out" />
                <x-ts:button sm outline color="slate" icon="tabler.maximize" @click="chart?.fit()" tooltip="Fit to Screen" />
                <x-ts:button sm outline color="indigo" icon="tabler.arrows-maximize" @click="chart?.expandAll()" tooltip="Expand All" />
                <x-ts:button sm outline color="indigo" icon="tabler.arrows-minimize" @click="chart?.collapseAll()" tooltip="Collapse All" />
            </div>
        </div>
    </div>

    {{-- Org Chart Canvas Container --}}
    <div class="relative min-h-[550px] w-full overflow-hidden rounded-xl border border-slate-200 bg-slate-50/50 p-4 shadow-inner">
        <div x-ref="chartContainer" class="h-full w-full min-h-[500px]"></div>
    </div>
</div>
