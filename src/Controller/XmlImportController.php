<?php

namespace Drupal\migrate_drush_xml\Controller;

use Drupal\node\Entity\Node;

/**
 * Defines a controller that will return a single node json response.
 */
class XmlImportController {

  /**
   * Import node from a given xml feed url.
   */
  public function import() {
    // Create guzzleclient object to make request.
    $client = \Drupal::httpClient();
    try {
      $request = $client->get('http://feeds.feedburner.com/ndtvnews-top-stories.xml');
      $response = $request->getBody()->getContents();
      // Load the xml file.
      $responseXml = simplexml_load_string($response);

      // Iterate each item of the xml.
      foreach ($responseXml->channel->item as $key => $value) {
        $node = Node::create(['type' => 'article']);
        $node->set('title', (string) $value->title);
        $node->set('field_guid', $value->guid);
        $link = [
          'title' => '',
          'uri' => (string) $value->link,
        ];
        $data = file_get_contents((string) $value->StoryImage);
        $file_name = basename((string) $value->StoryImage);
        $file = file_save_data($data, "public://images/$file_name", FILE_EXISTS_REPLACE);

        $image = [
          'target_id' => $file->id(),
        ];
        $node->set('field_image', $image);

        $node->set('field_link', $link);
        // $node->set('created', date('d-m-Y h:i:s', strtotime('1/1/2015')));
        // $node->set('changed', date('d-m-Y h:i:s', strtotime('1/1/2015')));
        $node->set('uid', 1);
        $node->set('status', 1);
        $node->enforceIsNew();
        $node->save();
      }
    }
    catch (RequestException $e) {
      watchdog_exception('my_module', $e->getMessage());
    }
  }

}
