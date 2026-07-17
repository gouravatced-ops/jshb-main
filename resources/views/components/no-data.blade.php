@props(['colspan' => 1, 'message' => 'No data found', 'icon' => 'fa-folder-open', 'description' => null])

<tr>
    <td colspan="{{ $colspan }}" style="text-align: center; padding: 48px 20px; color: var(--text-light, #64748b);">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; opacity: 0.8;">
            <svg width="80" height="80" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 5px;">
                <rect x="16" y="8" width="32" height="48" rx="4" fill="#E2E8F0" />
                <path d="M16 12C16 9.79086 17.7909 8 20 8H36L48 20V52C48 54.2091 46.2091 56 44 56H20C17.7909 56 16 54.2091 16 52V12Z" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="2"/>
                <path d="M36 8V16C36 18.2091 37.7909 20 40 20H48" stroke="#CBD5E1" stroke-width="2" stroke-linejoin="round"/>
                <line x1="24" y1="28" x2="40" y2="28" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round"/>
                <line x1="24" y1="36" x2="40" y2="36" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round"/>
                <line x1="24" y1="44" x2="32" y2="44" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round"/>
                
                <circle cx="44" cy="44" r="10" fill="#F1F5F9" stroke="#94A3B8" stroke-width="2"/>
                <line x1="51" y1="51" x2="58" y2="58" stroke="#94A3B8" stroke-width="3" stroke-linecap="round"/>
                <path d="M41 41L47 47M47 41L41 47" stroke="#94A3B8" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <div>
                <h5 style="margin: 0; font-size: 16px; font-weight: 500; color: var(--text-dark, #333);">{{ $message }}</h5>
                @if($description)
                    <p style="margin: 6px 0 0; font-size: 13px; color: var(--text-light, #64748b);">{{ $description }}</p>
                @endif
            </div>
        </div>
    </td>
</tr>
