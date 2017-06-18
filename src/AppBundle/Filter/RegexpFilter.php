<?php

namespace AppBundle\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

final class RegexpFilter extends AbstractFilter
{
    protected function filterProperty(string $property, $value,
                                      QueryBuilder $queryBuilder,
                                      QueryNameGeneratorInterface $queryNameGenerator,
                                      string $resourceClass,
                                      string $operationName = null)
    {
        $parameterName = $queryNameGenerator->generateParameterName($property); // Generate a unique parameter name to avoid collisions with other filters
        $queryBuilder
            ->andWhere(sprintf('REGEXP(o.%s, :%s) = 1', $property, $parameterName))
            ->setParameter($parameterName, $value);
    }

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        $description = [];
        if (!empty($this->properties)) {
            foreach ($this->properties as $property => $strategy) {
                $description['regexp_'.$property] = [
                    'property' => $property,
                    'type' => 'string',
                    'required' => false,
                    'swagger' => ['description' => 'Filter using a regex. This will appear in the Swagger documentation!'],
                ];
            }
        }

        return $description;
    }
}