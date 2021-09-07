<?php

namespace Drupal\jugaad\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use Drupal\Core\Render\Markup;

/**
 * Plugin implementation of the 'qrcode_link_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "qrcode_link_field_formatter",
 *   label = @Translation("QR Code"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class QRCodeLinkFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      // Get a URL from the field and make it absolute.
      $url = $item->getUrl()->setAbsolute(TRUE)->toString();
      // Just in case, sanitize entered URL.
      $productUrl =  UrlHelper::filterBadProtocol($url);

      $qrCode = new QrCode();

      $qrCode->setText($productUrl)
        ->setSize(300)
        ->setPadding(10)
        ->setErrorCorrection('high')
        ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
        ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
        ->setLabel('Scan here on your mobile')
        ->setLabelFontSize(16)
        ->setDrawBorder(2)
        ->setImageType(QrCode::IMAGE_TYPE_PNG);

      $displayQrcode = Markup::create('<img src="data:' . $qrCode->getContentType() . ';base64,' . $qrCode->generate() . '" />');

      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => $displayQrcode,
      ];
    }

    return $elements;
  }

}
