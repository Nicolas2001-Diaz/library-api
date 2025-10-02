<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user'        => [
                'id'    => $this->user?->id,
                'name'  => $this->user?->name,
                'email' => $this->user?->email,
            ],
            'book'        => [
                'id'     => $this->book?->id,
                'title'  => $this->book?->title,
                'author' => $this->book?->author,
                'genre'  => $this->book?->genre,
            ],
            'quantity'    => $this->quantity,
            'loan_date'   => optional($this->loan_date)->toDateString(),
            'due_date'    => optional($this->due_date)->toDateString(),
            'returned_at' => optional($this->returned_at)?->toISOString(),
            'created_at'  => optional($this->created_at)?->toISOString(),
            'updated_at'  => optional($this->updated_at)?->toISOString(),
        ];
    }
}
