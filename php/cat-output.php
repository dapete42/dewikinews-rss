<?php

#
# output.php
#
# Functions to write an RSS 2.0 file (and some other formats) to the output.
#

#
# rss2_render ( $channel, $items )
#
# $channel is an array with the following contents (* is required for valid
# stream):
#
#  *title           Title for the stream
#  *description     Description of the stream
#  *link            Link to the stream URL
#   language        de, en etc.
#   copyright       copyright info
#   pubDate         date (RFC 882)
#
# $items is an array of arrays, where each of the array elements contains the
# following:
#
#  *title	    Title of the item
#  *link	    Link to full contents of item
#   description     Long description text for item
#   pubDate         Date of item (RFC 882)
#   source	    Name of source
#   source_url      URL for source
#   guid	    guid (unique identifier) for post
#   guid_permalink  Flag to set isPermaLink for the guid
#   categories      Array of categories for the item
#

function rss2_render ($channel, $items) {

  # Set content-type
  header ("Content-type: text/xml; charset=utf-8");

  # Preamble
  echo "<?xml version=\"1.0\"?>\n";
  # This adds a stylesheet so the feed can be viewn directly in a browser.
  echo '<?xml-stylesheet href="rss.css" type="text/css"?>';
  echo "<rss version=\"2.0\">\n";
  echo "<channel>\n";

  # Start with channel output
  echo_param ($channel, 'title');
  echo_param ($channel, 'description');
  echo_param ($channel, 'link');
  echo_param_if ($channel, 'language');
  echo_param_if ($channel, 'copyright');
  echo_param_if ($channel, 'pubDate');

  # Finally the items themselves
  if ($items) {
    foreach ($items as $item) {
      echo "<item>\n";
      echo_param ($item, 'title');
      echo_param ($item, 'link');
      # guid with param is more complicated
      if (@$item['guid']) {
        if (@$item['guid_permalink']) {
	  echo '<guid>'.htmlspecialchars($item['guid'])."</guid>\n";
	}
        else {
          echo '<guid isPermaLink="false">'.htmlspecialchars($item['guid'])."</guid>\n";
        }
      }
      # As is the source
      if (@$item['source']) {
        if (@$item['source_url']) {
          echo '<source url='.htmlspecialchars($item['source_url']).'>'.htmlspecialchars($item['source'])."</source>\n";
        }
        else {
       	  echo_param ($item, 'source');
	}
      }

      echo_param_if ($item, 'pubDate');
      echo_param_if ($item, 'description');

      # All categories get added
      if (@$item['categories']) {
        foreach ($item['categories'] as $category) {
          echo '<category>' . htmlspecialchars($category) . "</category>\n";
        }
      }
      echo "</item>\n";
    }
  }

  # Postamble
  echo "</channel>\n";
  echo "</rss>\n";

}

function echo_param ($array, $paramname) {
  echo '<'.$paramname.'>'.htmlspecialchars($array[$paramname]).'</'.$paramname.">\n";
}

function echo_param_if ($array, $paramname) {
  if (@$array[$paramname]) {
    echo_param ($array, $paramname);
  }
}

?>
