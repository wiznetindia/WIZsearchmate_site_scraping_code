<?php
//set_time_limit(300);
require_once 'simple_html_dom.php';

$baseUrl = 'http://chatgpt.cafe24.com/';
$searchQuery = 'W5500';
$url = $baseUrl . $searchQuery;
$totalResults = '';
$html = file_get_html($baseUrl . '?s=' . $searchQuery); //page-1
foreach ($html->find(".archive-subtitle p") as $totalResultsElement) {
    $totalResults = $totalResultsElement->plaintext;
    $totalResults = filter_var($totalResults, FILTER_SANITIZE_NUMBER_INT);
    // echo $totalResults;
}

$answer = array();
// if (!empty($html)) {
//     $i = 0;
//     foreach ($html->find("article header h2 ") as $divClass) {
//         foreach ($divClass->find("a") as $aTag) {
//             $answer[$i]['url'] = $aTag->getAttribute('href');
//             $answer[$i]['title'] = $aTag->plaintext;
//             $i++;
//         }
//     }
// }

$resultsPerPage = 10;
$totalPages = ceil($totalResults / $resultsPerPage);
//echo "total pages=" . $totalPages;
$pageNumber = 1; // Start with the second page
$urlPattern = 'page/{page}/?s=' . $searchQuery;
$i = 0;

while ($pageNumber <= $totalPages) { // Adjust the condition based on the total number of pages available
    // Generate the URL for the current page
    $pageUrl = str_replace('{page}', $pageNumber, $urlPattern);
    //echo $baseUrl . $pageUrl . "<br>";
    // Make an HTTP request and retrieve the page content

    $html = file_get_html($baseUrl . $pageUrl);

    if (!empty($html)) {

        foreach ($html->find("article header h2 ") as $divClass) {
            foreach ($divClass->find("a") as $aTag) {
                $answer[$i]['url'] = $aTag->getAttribute('href');
                $answer[$i]['title'] = $aTag->plaintext;
                $i++;
            }
        }
    }
    // Move to the next page
    $pageNumber++;
}

// print_r($answer);
echo "Web srcaping Result of Site: <b>$baseUrl</b> with Search Pattern: <b>$searchQuery</b><br><br>";

?>
<table>
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Title</th>
            <th>URL</th>
        </tr>
    </thead>
    <tbody>

        <?php $n = 1;
        foreach ($answer as $titleUrl) { ?>
            <tr>
                <td><?php echo $n; ?></td>
                <td><?php echo  $titleUrl['title']; ?></td>
                <td><?php echo "<a href='{$titleUrl['url']}' target='__blank'>" . $titleUrl['url'] . "</a>" ?></td>
            </tr>

        <?php $n++;
        } ?>
    </tbody>
</table>