<?php
          // set the number of records per page
          $records_per_page = 25;

          // get the total number of records
          $sql_count = "SELECT COUNT(*) AS count FROM articles";
          $result_count = $connection->query($sql_count);
          $row_count = $result_count->fetch_assoc();
          $total_records = $row_count['count'];

          // calculate the total number of pages
          $total_pages = ceil($total_records / $records_per_page);

          // get the current page number
          $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

          // calculate the offset for the query
          $offset = ($current_page - 1) * $records_per_page;

          // prepare sql statement with limit and offset
          $sql = "WITH total_incoming AS (
            SELECT articles.id AS article_id, SUM(order_lines.quantity) AS incoming_stock
            FROM order_lines
            JOIN orders
                ON order_lines.order_id = orders.id
            JOIN articles
                ON articles.id = order_lines.article_id
            WHERE orders.order_type = 0 
            GROUP BY articles.id
          ), total_outgoing AS (
            SELECT articles.id AS article_id, SUM(order_lines.quantity) AS outgoing_stock
            FROM order_lines
            JOIN orders
                ON order_lines.order_id = orders.id
            JOIN articles
                ON articles.id = order_lines.article_id
            WHERE orders.order_type = 1 
            GROUP BY articles.id
          )

          SELECT articles.id AS 'article_id', articles.name AS 'article_name', 
                COALESCE(SUM(total_incoming.incoming_stock), 0) - COALESCE(SUM(total_outgoing.outgoing_stock), 0) AS 'stock_level'
          FROM articles
          LEFT JOIN total_incoming
              ON articles.id = total_incoming.article_id
          LEFT JOIN total_outgoing
              ON articles.id = total_outgoing.article_id
          GROUP BY articles.id, articles.name
          LIMIT $records_per_page
          OFFSET $offset;";

          // execute the query
          $result = $connection->query($sql);

          // display the table
          echo '<table class="table table-striped table-sm" id="table">';
          echo '<thead>';
          echo '<tr>';
          echo '<th>ID</th>';
          echo '<th>Article name</th>';
          echo '<th>Stock level</th>';
          echo '</tr>';
          echo '</thead>';
          echo '<tbody>';
          while($row = $result->fetch_assoc()) {
            echo "<tr>
            <td>$row[article_id]</td>
            <td>$row[article_name]</td>
            <td>$row[stock_level]</td>
            </tr>";
          }
          echo '</tbody>';
          echo '</table>';

          // display the pagination
          if ($total_pages > 1) {
            echo '<nav aria-label="Page navigation">';
            echo '<ul class="pagination">';
            
            // Display "Previous" link if not on the first page
            if ($current_page > 1) {
              echo '<li class="page-item"><a class="page-link" href="?page='.($current_page-1).'">Previous</a></li>';
            }
            
            // Display page links
            $start_page = max(1, $current_page - 5);
            $end_page = min($total_pages, $current_page + 5);
            for ($i = $start_page; $i <= $end_page; $i++) {
              echo '<li class="page-item';
              if ($current_page == $i) {
                echo ' active';
              }
              echo '"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
            }
            
            // Display "Next" link if not on the last page
            if ($current_page < $total_pages) {
              echo '<li class="page-item"><a class="page-link" href="?page='.($current_page+1).'">Next</a></li>';
            }
            
            echo '</ul>';
            echo '</nav>';
          }
        ?>