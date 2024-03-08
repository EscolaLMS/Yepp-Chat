<?php

namespace EscolaLms\YeppChat\Http\Controllers\Swagger;

use EscolaLms\YeppChat\Http\Requests\GetYeppChatRequest;
use Illuminate\Http\JsonResponse;

interface YeppChatControllerSwagger
{
    /**
     * @OA\Post(
     *      path="/api/yepp-chat/{lessonId}",
     *      summary="Get a newly yepp chat",
     *      tags={"Yepp Chat"},
     *      description="Get Yepp Chat",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          name="lessonId",
     *          description="ID of lesson",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/GetYeppChatRequest")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfull operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="success",
     *                      type="boolean"
     *                  ),
     *                  @OA\Property(
     *                      property="data",
     *                      ref="#/components/schemas/YeppChatResource"
     *                  ),
     *                  @OA\Property(
     *                      property="message",
     *                      type="string"
     *                  )
     *              )
     *          )
     *      )
     * )
     */
    public function get(GetYeppChatRequest $request): JsonResponse;
}
