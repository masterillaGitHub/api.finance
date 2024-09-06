<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\StoreRequest as StoreRequest;
use App\Http\Requests\Tag\UpdateRequest as UpdateRequest;
use App\Http\Requests\Tag\SetSortingRequest as SetSortingRequest;
use App\Http\Resources\NormalizeResources\AnonymousResourceCollection;
use App\Http\Resources\TagResource as Resource;
use App\Models\Tag;
use App\Repositories\TagRepository as Repository;
use App\Services\TagService as Service;
use Illuminate\Http\Response;

class TagController extends Controller
{
    private Repository $repository;
    private Service $service;

    public function __construct()
    {
        $this->repository = new Repository();
        $this->service = new Service();
    }

    public function index(): AnonymousResourceCollection
    {
        $items = $this->repository->get();

        return Resource::collection($items);
    }

    /**
     * @throws \Throwable
     */
    public function store(StoreRequest $request): Resource
    {
        $data = $request->validated();

        $item = $this->service->store($data);

        $item = $this->repository->whereId($item->id);

        return Resource::make($item);
    }

    public function show(int $id): Resource
    {
        $item = $this->repository->whereId($id);

        return Resource::make($item);
    }

    /**
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, Tag $tag): Resource
    {
        $data = $request->validated();

        $item = $this->service->update($tag, $data);
        $item = $this->repository->whereId($item->id);

        return Resource::make($item);
    }

    public function destroy(Tag $tag): Response
    {
        $tag->delete();

        return response()->noContent();
    }

    public function setSorting(SetSortingRequest $request): Response
    {
        $data = $request->validated();

        $this->service->setSorting($data['orderNumbers']);

        return response()->noContent();
    }
}
