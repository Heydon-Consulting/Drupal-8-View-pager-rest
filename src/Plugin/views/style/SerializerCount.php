<?php

/**
 * @file
 * Contains \Drupal\view_pager_rest\Plugin\views\style\SerializerCount.
 */

namespace Drupal\view_pager_rest\Plugin\views\style;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\rest\Plugin\views\style\Serializer;

/**
 * The style plugin for serialized output formats.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *  id = "serializer",
 *  title = @Translation("Serializer with count"),
 *  help = @Translation("Serializes views row data using the Serializer component and adds a count."),
 *  display_types = {"data"}
 *    )
 */
class SerializerCount extends Serializer {

  /**
   * {@inheritdoc}
   */
  public function render() {

    $rows = array();
    $count = $this->view->pager->getTotalItems();
    $itemsInView = $this->view->pager->getItemsPerPage();

    foreach ($this->view->result as $row_index => $row) {
      $this->view->row_index = $row_index;
      $rows[] = $this->view->rowPlugin->render($row);
    }
    
    unset($this->view->row_index);

    if ((empty($this->view->live_preview))) {
      $content_type = $this->displayHandler->getContentType();
    } else {
      $content_type = !empty($this->options['formats']) ? reset($this->options['formats']) : 'json';
    }

    return $this->serializer->serialize([
      'results' => $rows,
      'count' => $count,
      'inView' => $itemsInView
    ], $content_type);
  }
}
