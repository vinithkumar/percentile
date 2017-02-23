<div class="divTable" style="width: 100%;" >
 <?php if($students != '') { ?>
    <div class="divTableBody">
    <div class="divTableRow">
    <div class="divTableCell"><strong>Name</strong></div>
    <div class="divTableCell"><strong>GPA</strong></div>
    <div class="divTableCell"><strong>Percentile Rank</strong></div>
    </div>
    <?php foreach($students as $val) { ?>
        <div class="divTableRow">
        <div class="divTableCell"><?php echo $val['name'];?></div>
        <div class="divTableCell"><?php echo utf8_encode($val['gpa']);?></div>
        <div class="divTableCell"><?php echo $val['percentile'];?></div>
        </div>
    <?php } ?>
    </div>
 <?php } ?>
</div>


