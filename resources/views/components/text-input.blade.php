@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => ' bg-slate-950 text-white border-slate-700 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm']) }}>
