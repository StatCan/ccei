<?php

namespace Drupal\ccei_indicators_entity;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Indicators entity.
 *
 * @see \Drupal\ccei_indicators_entity\Entity\IndicatorsEntity.
 */
class IndicatorsEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ccei_indicators_entity\Entity\IndicatorsEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished indicators entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published indicators entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit indicators entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete indicators entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add indicators entities');
  }


}
