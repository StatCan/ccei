<?php

namespace Drupal\ccei_indicators_entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Indicators entities.
 *
 * @ingroup ccei_indicators_entity
 */
class IndicatorsEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Indicators ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\ccei_indicators_entity\Entity\IndicatorsEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.indicators_entity.edit_form',
      ['indicators_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
