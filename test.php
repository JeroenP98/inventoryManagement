<div class="col-md-6">
          <label class="col-form-label col-sm-3">Order date</label>
          <div class="col-sm-6">
            <input type="date" class="form-control" name="order_date" required value="<?=$order_date?>">
          </div>
        </div>
        <div class="col-md-6">
          <label class="col-form-label col-sm-3">Shipping date</label>
          <div class="col-md-6">
            <input type="date" class="form-control" name="shipping_date" required value="<?=$shipping_date?>">
          </div>
        </div>
        <div class="col-md-6">
          <label class="col-form-label col-sm-3">Employee</label>
          <div class="col-md-6">
            <select class="form-select" name="employee_id" required>
              <option value="<?=$_SESSION["user_id"]?>"><?=$_SESSION["full_name"]?></option>
              <?php
                $sql = "SELECT id, CONCAT(first_name,' ',last_name) AS `name` FROM employees ORDER BY name";
                $result = mysqli_query($connection, $sql);
                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                  }
                }
              ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <label class="col-form-label col-sm-3">Company</label>
          <div class="col-sm-6">
            <select class="form-select" name="company_id" required>
              <option value="<?=$company_id?>"><?=$company_name?></option>
              <?php
                $sql = "SELECT id, name FROM companies ORDER BY name";
                $result = mysqli_query($connection, $sql);
                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                  }
                }
              ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="col-sm-6 form-check mb-5">
            <input type="hidden" name="is_finalized" value="0">
            <input type="checkbox" name="is_finalized" value="1" class="form-check-input" <?php if($is_finalized == 1) echo "checked"; ?>>
            <label class="form-check-label">Is the order finalized?</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100 mb-3">Save changes</button>
          </div>
          <div class="col-md-3">
            <a href="<?php
              if($order_type == 0){
                echo "GUI_incoming.php";
              } elseif($order_type == 1){
                echo "GUI_outgoing.php";
              }
              ?>" class="btn btn-danger w-100">Close
            </a>
          </div>
        </div>
