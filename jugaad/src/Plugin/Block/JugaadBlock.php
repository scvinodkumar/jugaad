<?php
/**
 * @file
 * Contains \Drupal\jugaad\Plugin\Block\jugaad.
 */

namespace Drupal\jugaad\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\NodeInterface;


/**
 * Provides a 'product link' block.
 *
 * @Block(
 *   id = "jugaad_block",
 *   admin_label = @Translation("Jugaad block"),
 *   category = @Translation("Custom")
 * )
 */
class JugaadBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof NodeInterface && $node->bundle() == 'products') {
      $qrCode = $node->field_app_purchase_link->view('default');
      return [
        '#type' => 'inline_template',
        '#template' => "To purchase this product on our app to avail exclusive app-only<br />{{ qrCode }}",
        '#context' => [
          'qrCode' => $qrCode
        ]
      ];
    }
  }
}
