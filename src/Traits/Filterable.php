<?php

namespace Wave8\Factotum\Base\Traits;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait Filterable
{
    public function applyFilters(&$query, ?array $searchFilters): void
    {
        $model = $query->getModel();

        foreach ($searchFilters as $field => $value) {

            if (! $model->isFillable($field)) {
                throw new BadRequestHttpException("Field '$field' does not exist or is not fillable.");
            }

            $operator = substr($value, 0, 1);
            if (in_array($operator, ['<', '>'])) {

                $value = substr($value, 1);
                $query = $query->where($field, $operator, $value);

            } else {
                $query = $query->where($field, 'LIKE', "%$value%");
            }

        }
    }
}
