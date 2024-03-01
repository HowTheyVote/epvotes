@props([
    'member' => null,
    'position' => null,
    'url' => null,
])

<x-list-item
    :avatar-url="$member->thumbnail_url ?: asset('/assets/placeholder.svg')"
    :title="$member->full_name"
    :subtitle="$member->group->abbreviation.' · '.$member->country->label"
    :position="$position"
    :url="route('members.show', ['member' => $member])"
    search-key="{{ $member->full_name }} {{ $member->country->label }} {{ $member->group->abbreviation }} {{ $member->group->name }}"
    :filter-key="$position->label"
    {{ $attributes }}
/>
