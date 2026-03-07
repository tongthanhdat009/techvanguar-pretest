<?php

namespace App\Services;

use App\Models\Deck;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class CsvImportService
{
    /**
     * Import flashcards from CSV file into a deck.
     *
     * @param  Deck  $deck  The deck to import flashcards into
     * @param  UploadedFile  $file  The CSV file to import
     * @return int The number of flashcards imported
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function importFlashcards(Deck $deck, UploadedFile $file): int
    {
        $handle = fopen($file->getRealPath(), 'r');
        $headers = $handle ? fgetcsv($handle) : false;

        if (! $handle || ! is_array($headers)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to read CSV file.');
        }

        $importedCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = $this->combineRowWithHeaders($row, $headers);

            $deck->flashcards()->create([
                'front_content' => $data['front_content'] ?? '',
                'back_content' => $data['back_content'] ?? '',
                'image_url' => $data['image_url'] ?? null,
                'audio_url' => $data['audio_url'] ?? null,
                'hint' => $data['hint'] ?? null,
            ]);

            $importedCount++;
        }

        fclose($handle);

        return $importedCount;
    }

    /**
     * Get the expected CSV headers for flashcard import.
     *
     * @return array<string>
     */
    public static function getExpectedHeaders(): array
    {
        return ['front_content', 'back_content', 'image_url', 'audio_url', 'hint'];
    }

    /**
     * Validate that the CSV file contains the required headers.
     *
     * @param  array<string>  $headers
     * @return bool
     */
    public function validateHeaders(array $headers): bool
    {
        $requiredHeaders = ['front_content', 'back_content'];
        $normalizedHeaders = array_map('strtolower', $headers);

        foreach ($requiredHeaders as $required) {
            if (! in_array(strtolower($required), $normalizedHeaders, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Combine a CSV row with headers, handling missing values.
     *
     * @param  array  $row
     * @param  array<string>  $headers
     * @return array<string, mixed>
     */
    private function combineRowWithHeaders(array $row, array $headers): array
    {
        $paddedRow = array_pad($row, count($headers), null);

        return array_combine($headers, $paddedRow) ?: [];
    }
}
