<?php

namespace Drupal\ila_registration\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Defines an "only one" validation constraint for entities.
 *
 * @Constraint(
 *   id = "IlaEntityOnlyOne",
 *   label = @Translation("Entity is only one for user", context = "Validation"),
 * )
 */
class EntityOnlyOne extends Constraint {
}
