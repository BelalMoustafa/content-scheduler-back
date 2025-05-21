<?php

namespace Modules\Posts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Posts\Http\Requests\CreatePostRequest;
use Modules\Posts\Http\Requests\UpdatePostRequest;
use Modules\Posts\Services\PostService;
use App\Traits\ApiResponse;

class PostController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PostService $postService
    ) {}

    /**
     * Get all posts
     */
    public function index(): JsonResponse
    {
        $posts = $this->postService->getAllPosts();
        return $this->okResponse('Posts retrieved successfully.', $posts);
    }

    /**
     * Get specific post
     */
    public function show(int $id): JsonResponse
    {
        try {
            $post = $this->postService->getPost($id);
            return $this->okResponse('Post retrieved successfully.', $post);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Create new post
     */
    public function store(CreatePostRequest $request): JsonResponse
    {
        try {
            $post = $this->postService->createPost(
                $request->user(),
                $request->validated()
            );
            return $this->okResponse('Post created successfully.', $post);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update post
     */
    public function update(UpdatePostRequest $request, int $id): JsonResponse
    {
        try {
            $post = $this->postService->updatePost($id, $request->validated());
            return $this->okResponse('Post updated successfully.', $post);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Delete post
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->postService->deletePost($id);
            return $this->okResponse('Post deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Publish post
     */
    public function publish(int $id): JsonResponse
    {
        try {
            $post = $this->postService->getPost($id);
            $this->postService->publishPost($post);
            return $this->okResponse('Post published successfully.', $post);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
