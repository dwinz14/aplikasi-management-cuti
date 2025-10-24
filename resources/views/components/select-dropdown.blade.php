@props(['name', 'label', 'options' => [], 'selected' => null, 'placeholder' => 'Pilih...', 'disabled' => false])

<div x-data="dropdownSelect({
    options: @js($options),
    selected: @js($selected),
    placeholder: @js($placeholder)
})" @click.away="open = false" x-cloak class="relative w-full">

    <x-input-label :for="$name" :value="$label" class="mb-2 font-medium text-gray-800 dark:text-gray-200" />

    <!-- Hidden input for form -->
    <input type="hidden" name="{{ $name }}" x-model="selectedValue">

    <!-- Select button -->
    <button type="button" @click="toggle()" :disabled="$disabled"
        class="w-full flex justify-between items-center px-3 py-2.5 text-sm rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500/30 transition-all duration-200 hover:border-gray-400 dark:hover:border-slate-600 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">

        <span x-text="selectedLabel" class="truncate"></span>

        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200"
            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Options -->
    <div x-show="open" x-transition.opacity.scale.95
        class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-lg max-h-56 overflow-auto ring-1 ring-black/5 dark:ring-white/10">

        <ul class="py-1 text-sm">
            <template x-for="option in options" :key="option.id">
                <li @click="select(option)"
                    class="px-3 py-2 cursor-pointer hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors duration-100"
                    :class="{
                        'text-primary-600 dark:text-primary-400 font-medium bg-primary-100/30 dark:bg-primary-900/30': selectedValue ==
                            option.id,
                        'text-gray-800 dark:text-gray-200': selectedValue != option.id
                    }"
                    x-text="option.name">
                </li>
            </template>
        </ul>
    </div>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>

<!-- Alpine Component Script -->
<script>
    function dropdownSelect({
        options,
        selected,
        placeholder
    }) {
        return {
            open: false,
            options: options,
            selectedValue: selected,
            selectedLabel: placeholder,
            toggle() {
                this.open = !this.open
            },
            select(option) {
                this.selectedValue = option.id;
                this.selectedLabel = option.name;
                this.open = false;
            },
            init() {
                const option = this.options.find(o => o.id == this.selectedValue);
                if (option) this.selectedLabel = option.name;
            }
        };
    }
</script>
