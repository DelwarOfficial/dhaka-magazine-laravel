{{--
    Location News Filter Component — জেলার সংবাদ ফিল্টার
    ─────────────────────────────────────────────────────────────────────────────
    Usage:
        <x-location-news-filter
            :divisions="$divisions"
            :selected-division="$division ?? ''"
            :selected-district="$district ?? ''"
            :selected-upazila="$upazila ?? ''"
        />

    Props:
        $divisions         — array of [english_name => bengali_name] pairs
        $selectedDivision  — currently active division (English key, e.g. "Dhaka")
        $selectedDistrict  — currently active district (English key, e.g. "Gazipur")
        $selectedUpazila   — currently active upazila  (English key, e.g. "Kaliakair")

    JS behaviour:
        - Division dropdown change → AJAX fetch districts → populate district dropdown
        - District dropdown change → AJAX fetch upazilas → populate upazila dropdown
        - Search button submits form to /saradesh with query string params
    ─────────────────────────────────────────────────────────────────────────────
--}}

@props([
    'divisions'        => [],
    'selectedDivision' => '',
    'selectedDistrict' => '',
    'selectedUpazila'  => '',
])

{{-- ── Filter wrapper ─────────────────────────────────────────────────────── --}}
<div class="w-full bg-surface border border-border rounded-md px-4 py-3"
     id="location-news-filter">

    <form
        id="location-filter-form"
        action="{{ route('category.parent', 'country-news') }}"
        method="GET"
        class="flex flex-col md:flex-row md:items-center gap-3"
    >
        {{-- ── Label ──────────────────────────────────────────────────────── --}}
        <div class="flex items-center gap-2 shrink-0">
            {{-- Red target/location icon (SVG) --}}
            <span class="text-[#d71920] text-[20px] leading-none" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="currentColor" class="w-5 h-5 inline-block">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 17.93V18a1 1 0 0 0-2 0v1.93A8 8 0 0 1 4.07 13H6a1 1 0 0 0 0-2H4.07A8 8 0 0 1 11 4.07V6a1 1 0 0 0 2 0V4.07A8 8 0 0 1 19.93 11H18a1 1 0 0 0 0 2h1.93A8 8 0 0 1 13 19.93Z"/>
                </svg>
            </span>
            <span class="font-bold text-[14px] text-[#d71920] font-serif whitespace-nowrap">
                আমার এলাকার খবর
            </span>
        </div>

        {{-- ── Dropdowns ──────────────────────────────────────────────────── --}}
        <div class="flex flex-col md:flex-row gap-3 flex-1">

            {{-- Division (বিভাগ) --}}
            <div class="flex-1">
                <label for="filter-division" class="sr-only">বিভাগ</label>
                <select
                    id="filter-division"
                    name="division"
                    class="location-select w-full border border-border bg-surface text-[14px] text-fg rounded-sm px-3 py-2 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#e2231a]/30 transition-colors"
                >
                    <option value="">বিভাগ</option>
                    @foreach($divisions as $engName => $bnName)
                        <option value="{{ $engName }}"
                            {{ $selectedDivision === $engName ? 'selected' : '' }}>
                            {{ $bnName }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- District (জেলা) — populated via AJAX --}}
            <div class="flex-1">
                <label for="filter-district" class="sr-only">জেলা</label>
                <select
                    id="filter-district"
                    name="district"
                    disabled
                    class="location-select w-full border border-border bg-surface text-[14px] text-fg rounded-sm px-3 py-2 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#e2231a]/30 transition-colors disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <option value="">জেলা</option>
                </select>
            </div>

            {{-- Upazila (উপজেলা) — populated via AJAX --}}
            <div class="flex-1">
                <label for="filter-upazila" class="sr-only">উপজেলা</label>
                <select
                    id="filter-upazila"
                    name="upazila"
                    disabled
                    class="location-select w-full border border-border bg-surface text-[14px] text-fg rounded-sm px-3 py-2 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#e2231a]/30 transition-colors disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <option value="">উপজেলা</option>
                </select>
            </div>

        </div>{{-- /.dropdowns --}}

        {{-- ── Search button ──────────────────────────────────────────────── --}}
        <button
            type="submit"
            id="location-filter-submit"
            class="flex items-center justify-center gap-2 px-6 py-2.5 bg-[#2f3da6] hover:bg-[#303f9f] text-white font-bold text-[14px] rounded-sm transition-colors whitespace-nowrap font-serif shrink-0 md:min-w-[110px]"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                 fill="currentColor" class="w-4 h-4" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                    clip-rule="evenodd" />
            </svg>
            খুঁজুন
        </button>

    </form>{{-- /.form --}}

</div>{{-- /.location-news-filter --}}

{{-- ── JavaScript: Dependent dropdowns via fetch() ────────────────────────── --}}
<script>
(function () {
    'use strict';

    const divisionEl = document.getElementById('filter-division');
    const districtEl = document.getElementById('filter-district');
    const upazilaEl  = document.getElementById('filter-upazila');

    // Pre-selected values passed from the server (for page refresh retention)
    const preSelectedDistrict = @json($selectedDistrict);
    const preSelectedUpazila  = @json($selectedUpazila);

    /**
     * Populate a <select> with options.
     * @param {HTMLSelectElement} selectEl
     * @param {Array|Object}      items    — array of strings OR array of {name, name_bangla} objects
     * @param {string}            placeholder
     * @param {string}            preSelected — value to pre-select
     */
    function populateSelect(selectEl, items, placeholder, preSelected = '') {
        // Clear + reset
        selectEl.innerHTML = `<option value="">${placeholder}</option>`;

        if (!items || (Array.isArray(items) && items.length === 0)) {
            selectEl.disabled = true;
            return;
        }

        if (Array.isArray(items)) {
            items.forEach(function (item) {
                if (typeof item === 'string') {
                    // Backward compatible upazila payload (plain string)
                    const opt = document.createElement('option');
                    opt.value    = item;
                    opt.textContent = item;
                    if (item === preSelected) opt.selected = true;
                    selectEl.appendChild(opt);
                } else if (item.slug) {
                    // Upazila: {slug, name_bn}
                    const opt = document.createElement('option');
                    opt.value       = item.slug;
                    opt.textContent = item.name_bn || item.slug;
                    if (item.slug === preSelected) opt.selected = true;
                    selectEl.appendChild(opt);
                } else {
                    // District: {name, name_bangla}
                    const opt = document.createElement('option');
                    opt.value       = item.name;
                    opt.textContent = item.name_bangla || item.name;
                    if (item.name === preSelected) opt.selected = true;
                    selectEl.appendChild(opt);
                }
            });
        }

        selectEl.disabled = false;
    }

    /** Fetch districts for the selected division, then trigger upazila load if needed */
    async function loadDistricts(division, preSelectDistrict, preSelectUpazila) {
        // Reset downstream selects
        populateSelect(districtEl, [], 'জেলা');
        populateSelect(upazilaEl,  [], 'উপজেলা');

        if (!division) return;

        try {
            const url = `/api/saradesh/districts?division=${encodeURIComponent(division)}`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Network error');

            const data = await res.json();
            populateSelect(districtEl, data, 'জেলা', preSelectDistrict);

            // If a district was pre-selected (page reload), also load its upazilas
            if (preSelectDistrict) {
                await loadUpazilas(division, preSelectDistrict, preSelectUpazila);
            }
        } catch (err) {
            console.error('[LocationFilter] Failed to load districts:', err);
        }
    }

    /** Fetch upazilas for the selected division + district */
    async function loadUpazilas(division, district, preSelectUpazila) {
        populateSelect(upazilaEl, [], 'উপজেলা');

        if (!division || !district) return;

        try {
            const url = `/api/saradesh/upazilas?division=${encodeURIComponent(division)}&district=${encodeURIComponent(district)}`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Network error');

            const data = await res.json();
            populateSelect(upazilaEl, data, 'উপজেলা', preSelectUpazila);
        } catch (err) {
            console.error('[LocationFilter] Failed to load upazilas:', err);
        }
    }

    // ── Event listeners ──────────────────────────────────────────────────────

    divisionEl.addEventListener('change', function () {
        loadDistricts(this.value, '', '');
    });

    districtEl.addEventListener('change', function () {
        loadUpazilas(divisionEl.value, this.value, '');
    });

    // ── On page load: restore filter state if query params are present ────────
    (function restoreState() {
        const division = divisionEl.value;
        if (division) {
            // Load districts + upazilas to restore the full dropdown state
            loadDistricts(division, preSelectedDistrict, preSelectedUpazila);
        }
    })();

}());
</script>
