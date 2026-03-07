<?php

namespace App\Services;

use App\Models\Deck;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExportService
{
    /**
     * Export flashcards from a deck to CSV format.
     *
     * @param  Deck  $deck  The deck to export
     * @param  string|null  $filename  Custom filename (without extension)
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportFlashcards(Deck $deck, ?string $filename = null): StreamedResponse
    {
        $deck->load('flashcards');

        $filename = $filename ?? Str::slug($deck->title);

        return response()->streamDownload(
            function () use ($deck) {
                $output = fopen('php://output', 'w');

                // Write headers
                fputcsv($output, $this->getHeaders());

                // Write flashcard data
                foreach ($deck->flashcards as $flashcard) {
                    fputcsv($output, $this->mapFlashcardToRow($flashcard));
                }

                fclose($output);
            },
            $filename.'.csv'
        );
    }

    /**
     * Get the CSV headers for flashcard export.
     *
     * @return array<string>
     */
    public function getHeaders(): array
    {
        return ['front_content', 'back_content', 'image_url', 'audio_url', 'hint'];
    }

    /**
     * Map a flashcard model to a CSV row.
     *
     * @param  \App\Models\Flashcard  $flashcard
     * @return array<string, mixed>
     */
    private function mapFlashcardToRow($flashcard): array
    {
        return [
            'front_content' => $flashcard->front_content,
            'back_content' => $flashcard->back_content,
            'image_url' => $flashcard->image_url,
            'audio_url' => $flashcard->audio_url,
            'hint' => $flashcard->hint,
        ];
    }
}
