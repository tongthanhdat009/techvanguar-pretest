{{-- Page Title Helper - For use in @section('title') or <title> tag --}}
@props([
    'title' => '',
    'suffix' => ' – Flashcard Learning Hub'
])

{{ $title . $suffix }}
