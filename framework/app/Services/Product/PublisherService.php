<?php

namespace App\Services\Product;

use App\Http\Requests\Product\PublisherRequest;
use App\Models\AbstractModel;
use App\Models\Product\Publisher;
use App\Repositories\Product\PublisherRepository;
use App\Services\AbstractService;
use App\Services\Tables;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Storage;

/**
 * Class PublisherService
 * @package App\Services\Product
 */
class PublisherService extends AbstractService
{
    use Tables;

    /**
     * PublisherService constructor.
     *
     * @param PublisherRequest    $request
     * @param Guard               $auth
     * @param PublisherRepository $repository
     */
    public function __construct(PublisherRequest $request, Guard $auth, PublisherRepository $repository)
    {
        parent::__construct($request, $auth, $repository);

        $this->setFormDataRules();
    }

    /**
     * @return array
     */
    public function tables(): array
    {
        return $this->getTable(
            $this->request,
            $this->repository->getBuilder(),
            [
                'name'
            ]
        );
    }

    /**
     * @return AbstractModel
     */
    public function store(): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        return $this->repository->store($this->processFields());
    }

    /**
     * @param bool $restore
     *
     * @return AbstractModel
     */
    public function update($restore = false): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        /** @var Publisher $model */
        $model  = $this->request->getModel();
        $fields = $this->processFields();

        if (isset($fields['logo']) && ($fields['logo'] !== $model->logo)) {
            $this->deleteImage($model->logo);
        }

        return $this->repository->update($this->request->getModel(), $this->processFields(), $restore);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function destroy(): bool
    {
        /** @var Publisher $publisher */
        $publisher = $this->request->getModel();

        if (!$publisher->products->isEmpty()) {
            throw new \Exception('You cannot delete this publisher because is has products attached to it.');
        }

        return $this->repository->destroy($this->request->getModel());
    }

    /**
     * @return PublisherService
     */
    protected function setFormDataRules(): self
    {
        $this->setReplacedData([
            'logo' => function ($logo, $attributes) {
                return $this->saveLogo(snake_case($attributes['name']), $logo);
            },
        ]);

        return $this;
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return [
            'name'     => $this->request->input('name'),
            'logo'     => $this->request->input('logo'),
            'position' => $this->request->input('position'),
        ];
    }

    /**
     * @param string $name
     * @param string $content
     *
     * @return string
     */
    private function saveLogo(string $name, string $content): string
    {
        list($type, $imageData) = explode(';', $content);
        list(, $extension) = explode('/', $type);
        list(, $imageData) = explode(',', $imageData);

        $imageName = str_slug($name) . '.' . $extension;
        $imageData = base64_decode($imageData);

        Storage::disk('public')->put('publishers/' . $imageName, $imageData);

        return $imageName;
    }

    /**
     * @param string $name
     *
     * @return PublisherService
     */
    private function deleteImage(string $name = null): self
    {
        if (!empty($name)) {
            Storage::disk('public')->delete('products/' . $name);
        }

        return $this;
    }
}