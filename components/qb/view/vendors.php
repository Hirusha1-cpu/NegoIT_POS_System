<?php
include_once 'template/header.php';
?>

<style>
    table {
        font-family: Calibri;
    }

    .tbl-header {
        font-family: Calibri;
        color: maroon;
        font-weight: bold;
        background: #EEEEEE;
        min-width: 1300px;
    }

    .td-style {
        background-color: silver;
        color: navy;
        font-family: Calibri;
        font-size: 14pt;
    }

    .styled-table {
        border-collapse: collapse;
        margin-top: 25px;
        font-family: Calibri;
        min-width: 1300px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
    }

    .styled-table thead tr {
        background-color: #3f83d7;
        color: #ffffff;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 5px 15px;
    }

    .styled-table tbody tr {
        border-bottom: thin solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
        /* background-color: #f3f3f3; */
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #205081;
    }

    .styled-table tbody tr:hover {
        background-color: #f3f3f3;
    }
</style>

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:30px;" /></div>

<div>
    <table class="styled-table" align="center" border="0">
        <thead>
            <tr>
                <td colspan="8" style="color: black; background: #dddddd;" class="td-style">
                    <strong style="padding-left: 10px">QuickBooks Vendors List</strong>
                </td>
            </tr>
            <tr>
                <th width="20px" align="center">#</th>
                <th width="120px" align="left">Name</th>
                <!-- <th width="120px" align="left">Email</th>
                <th width="150px" align="left">Phone</th>
                <th width="150px" align="left">Mobile</th> -->
                <th width="150px" align="right">Balance</th>
                <th width="150px" align="center">Active</th>
                <th width="150px" align="center">Create Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($vendors)) {
                foreach ($vendors as $vendor) {
                    echo '<tr>';
                    echo '<td align="center">' . $vendor['index'] . '</td>';
                    echo '<td>' . $vendor['CompanyName'] . '</td>';
                    // echo '<td>' . $vendor['PrimaryEmailAddr'] . '</td>';
                    // echo '<td>' . $vendor['PrimaryPhone'] . '</td>';
                    // echo '<td>' . $vendor['Mobile'] . '</td>';
                    echo '<td align="right">' . $vendor['Balance'] . '</td>';
                    echo '<td align="center">' . $vendor['Active'] . '</td>';
                    echo '<td align="center">' . $vendor['CreateTime'] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="8">No data available</td></tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th width="20px" align="center">#</th>
                <th width="120px" align="left">Name</th>
                <!-- <th width="120px" align="left">Email</th>
                <th width="150px" align="left">Phone</th>
                <th width="150px" align="left">Mobile</th> -->
                <th width="150px" align="right">Balance</th>
                <th width="150px" align="center">Active</th>
                <th width="150px" align="center">Create Time</th>
            </tr>
        </tfoot>
    </table>
</div>
<?php
include_once 'template/footer.php';
?>