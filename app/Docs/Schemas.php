<?php
/**
 * @OA\Info(
 *   title="Library API",
 *   version="1.0.0",
 *   description="Documentación de la API de Biblioteca"
 * )
 */

/**
 * @OA\Schema(
 *   schema="Book",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="title", type="string", example="Clean Code"),
 *   @OA\Property(property="author", type="string", example="Robert C. Martin"),
 *   @OA\Property(property="genre", type="string", example="Programming"),
 *   @OA\Property(property="stock", type="integer", example=5),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-02T15:00:00Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-02T15:10:00Z")
 * )
 */

/**
 * @OA\Schema(
 *   schema="BookCreate",
 *   type="object",
 *   required={"title","author","genre","stock"},
 *   @OA\Property(property="title", type="string", example="Clean Code"),
 *   @OA\Property(property="author", type="string", example="Robert C. Martin"),
 *   @OA\Property(property="genre", type="string", example="Programming"),
 *   @OA\Property(property="stock", type="integer", example=5)
 * )
 */

/**
 * @OA\Schema(
 *   schema="PaginatedMeta",
 *   type="object",
 *   @OA\Property(property="current_page", type="integer", example=1),
 *   @OA\Property(property="per_page", type="integer", example=10),
 *   @OA\Property(property="total", type="integer", example=42),
 *   @OA\Property(property="last_page", type="integer", example=5)
 * )
 */

/**
 * @OA\Schema(
 *   schema="ValidationError",
 *   type="object",
 *   @OA\Property(property="success", type="boolean", example=false),
 *   @OA\Property(property="message", type="string", example="Validation error"),
 *   @OA\Property(
 *     property="errors",
 *     type="object",
 *     example={"email": {"The email has already been taken."}}
 *   )
 * )
 */

/**
 * @OA\Schema(
 *   schema="NotFoundError",
 *   type="object",
 *   @OA\Property(property="success", type="boolean", example=false),
 *   @OA\Property(property="message", type="string", example="Resource not found"),
 *   @OA\Property(property="errors", type="array", @OA\Items(type="string"), example={})
 * )
 */
