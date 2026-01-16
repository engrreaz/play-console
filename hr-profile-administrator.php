<?php
$pf_data = array();
$sql0 = "SELECT sum(amount) as bala, max(modifieddate) as upddate FROM providendfund where tid='$tid' and sccode='$sccode' ";
$result0pf = $conn->query($sql0);
if ($result0pf->num_rows > 0) {
    while ($row0 = $result0pf->fetch_assoc()) {
        $pf_balance = $row0['bala'];
        $upd_date = $row0['upddate'];
    }
}


?>

<div class="card text-center d-block" style="background:var(--normal); color:white; ">
    <div class="card-body page-info-box d-flex" style="background:var(--normal); color:white; ">
        <div class="col-2 st-pic text-center"></div>
        <div class="d-block">
            <div class="ps-3 pt-2 text-start d-flex">
                <div class="text-small pe-2">BDT </div>
                <div class="stname-eng text-white" style="font-size:32px;"><?php echo number_format($pf_balance, 2); ?>
                </div>
            </div>
            <div class="ps-3 pt-2 st-id text-small"><b>PF Balance</b> (Update on <?php echo date('d/m/Y H:i:s', strtotime($upd_date)); ?>)</div>
        </div>
    </div>
</div>


<div class="card text-center" style="background:var(--lighter);">
    <div class="card-body">
        <div style="text-align:left; padding-top:10px;">
            <table width="100%">
                <tr>
                    <td style="width:30px; padding-right:10px;"><i class="bi bi-person-circle menu-item-icon "></i></td>
                    <td colspan="2" class="text-small font-weight-bold ">
                        <?php echo '<b>' . $teacher_pfofile_data[0]['position'] . ' </b>(' . $teacher_pfofile_data[0]['slots'] . ')'; ?>
                        <br>
                        <?php echo $teacher_pfofile_data[0]['subjects']; ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="datam"><i class="bi bi-telephone-fill"></i></td>
                    <td class="datam-2"><?php echo $teacher_pfofile_data[0]['mobile']; ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="datam"><i class="bi bi-envelope-fill"></i></td>
                    <td class="datam-2"><?php echo $teacher_pfofile_data[0]['email']; ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="datam"><i class="bi bi-phone-fill"></i></td>
                    <td class="datam-2"><?php echo $teacher_pfofile_data[0]['emergency']; ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="datam"><i class="bi bi-droplet-fill"></i></td>
                    <td class="datam-2"><?php echo $teacher_pfofile_data[0]['bgroup']; ?></td>
                </tr>


                <tr>
                    <td colspan="3">
                        <hr style="margin:10px 0 ; padding:0; width:100%; height:1px;" />
                    </td>
                </tr>

                <tr>
                    <td style="width:30px; padding-right:10px;"><i class="bi bi-people-fill menu-item-icon "></i></td>
                    <td colspan="2" class="text-small font-weight-bold ">
                        <div class="float-end text-small text-muted">Father</div>
                        <div>
                            <?php echo $teacher_pfofile_data[0]['fname']; ?>
                        </div>
                        <div class="float-end text-small text-muted">Mother</div>
                        <div>
                            <?php echo $teacher_pfofile_data[0]['mname']; ?>
                        </div>
                        <div class="float-end text-small text-muted">Spouse</div>
                        <div>
                            <?php echo $teacher_pfofile_data[0]['spouse']; ?>
                        </div>

                    </td>
                </tr>



                <tr>
                    <td colspan="3">
                        <hr style="margin:10px 0 ; padding:0; width:100%; height:1px;" />
                    </td>
                </tr>


                <!-- *********************************************************************************** -->

                <tr>
                    <td rowspan="3" style="width:30px; padding-right:10px; vertical-align: top; padding-top:5px;"><i
                            class="bi bi-geo-alt-fill menu-item-icon "></i></td>
                    <td colspan="2" class="d-blockx">
                        <div class="float-start text-small text-muted">
                            <b>Present Address</b>
                        </div>
                        <div class="text-small text-dark">
                            <?php
                            $pre_add = $teacher_pfofile_data[0]['previll'];
                            $pre_add .= ', ' . $teacher_pfofile_data[0]['prepo'];
                            $pre_add2 = $teacher_pfofile_data[0]['preps'];
                            $pre_add2 .= ', ' . $teacher_pfofile_data[0]['predist'];
                            echo '<br>' . $pre_add . '<br>' . $pre_add2;
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="d-blockx">
                        <div class="float-start text-small text-muted">
                            <b>Permanent Address</b>
                        </div>
                        <div class="text-small text-dark">
                            <?php
                            $pre_add = $teacher_pfofile_data[0]['pervill'];
                            $pre_add .= ', ' . $teacher_pfofile_data[0]['perpo'];
                            $pre_add2 = $teacher_pfofile_data[0]['perps'];
                            $pre_add2 .= ', ' . $teacher_pfofile_data[0]['perdist'];
                            echo '<br>' . $pre_add . '<br>' . $pre_add2;
                            ?>
                        </div>
                    </td>
                </tr>






                <tr>
                    <td colspan="3">
                        <hr style="margin:10px 0 ; padding:0; width:100%; height:1px;" />
                    </td>
                </tr>



                <!-- ***************************************************** -->
                <tr>
                    <td rowspan="7" style="width:30px; padding-right:10px; vertical-align: top; padding-top:5px;"><i
                            class="bi bi-calendar-week-fill menu-item-icon "></i></td>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">First join</div>
                        <div class="text-small text-dark">
                            <?php echo date("l, d F, Y", strtotime($teacher_pfofile_data[0]['jdate'])); ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Join (This Institute)</div>
                        <div class="text-small text-dark">
                            <?php echo date("l, d F, Y", strtotime($teacher_pfofile_data[0]['fjdate'])); ?>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Date of Birth</div>
                        <div class="text-small text-dark">
                            <?php echo date("l, d F, Y", strtotime($teacher_pfofile_data[0]['dob'])); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Retirement</div>
                        <div class="text-small text-dark">
                            <?php
                            $lpr = date_create($teacher_pfofile_data[0]['dob']);
                            date_add($lpr, date_interval_create_from_date_string("60 years"));
                            date_sub($lpr, date_interval_create_from_date_string("1 days"));
                            echo date_format($lpr, "l, j F, Y");

                            ?>
                        </div>
                    </td>
                </tr>

                <!-- *************************************************************************** -->



                <tr>
                    <td colspan="3">
                        <hr style="margin:10px 0 ; padding:0; width:100%; height:1px;" />
                    </td>
                </tr>

                <tr>
                    <td rowspan="7" style="width:30px; padding-right:10px; vertical-align: top; padding-top:5px;"><i
                            class="bi bi-calendar-week-fill menu-item-icon "></i></td>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Religion</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['religion']; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">NID</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['nid']; ?>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">TIN No.</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['tin']; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">MPO Index</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['mpoindex']; ?>

                        </div>
                    </td>
                </tr>



                <!-- *************************************************************************************** -->


                <!-- ***************************************************** -->
                <tr>
                    <td colspan="3">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h6 class="pt-4 pb-2">MPO Account Information</h6>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr class="hr-line" />
                    </td>
                </tr>


                <tr>
                    <td rowspan="7" style="width:30px; padding-right:10px; vertical-align: top; padding-top:5px;"><i
                            class="bi bi-bank menu-item-icon "></i></td>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Account No.</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['accno']; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Bank Name</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['bankname']; ?>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Branch</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['branch']; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Routing No.</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['routing']; ?>
                        </div>
                    </td>
                </tr>

                <!-- *************************************************************************** -->


                <!-- ***************************************************** -->
                <tr>
                    <td colspan="3">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h6 class="pt-4 pb-2">Institute Related Account Info</h6>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr class="hr-line" />
                    </td>
                </tr>


                <tr>
                    <td rowspan="7" style="width:30px; padding-right:10px; vertical-align: top; padding-top:5px;"><i
                            class="bi bi-bank menu-item-icon "></i></td>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Account No.</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['accnosch']; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Bank Name</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['bnamesch']; ?>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Branch</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['bbrsch']; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Routing No.</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['routesch']; ?>
                        </div>
                    </td>
                </tr>

                <!-- *************************************************************************** -->


                <!-- ***************************************************** -->
                <tr>
                    <td colspan="3">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h6 class="pt-4 pb-2">PF Account Information</h6>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr class="hr-line" />
                    </td>
                </tr>


                <tr>
                    <td rowspan="7" style="width:30px; padding-right:10px; vertical-align: top; padding-top:5px;"><i
                            class="bi bi-bank menu-item-icon "></i></td>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Account No.</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['accnopf']; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Bank Name</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['bnamepf']; ?>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Branch</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['bbrpf']; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">Routing No.</div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['routepf']; ?>
                        </div>
                    </td>
                </tr>

                <!-- *************************************************************************** -->


                <!-- ***************************************************** -->
                <tr>
                    <td colspan="3">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h6 class="pt-4 pb-2">Custom Information</h6>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr class="hr-line" />
                    </td>
                </tr>


                <tr>
                    <td rowspan="7" style="width:30px; padding-right:10px; vertical-align: top; padding-top:5px;"><i
                            class="bi bi-info-circle-fill menu-item-icon "></i></td>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">
                            <?php echo $teacher_pfofile_data[0]['ex_1']; ?>
                        </div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['val_1']; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">
                            <?php echo $teacher_pfofile_data[0]['ex_2']; ?>
                        </div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['val_2']; ?>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">
                            <?php echo $teacher_pfofile_data[0]['ex_3']; ?>
                        </div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['val_3']; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr class="hr-line" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-end text-small text-muted">
                            <?php echo $teacher_pfofile_data[0]['ex_4']; ?>
                        </div>
                        <div class="text-small text-dark">
                            <?php echo $teacher_pfofile_data[0]['val_4']; ?>
                        </div>
                    </td>
                </tr>

                <!-- *************************************************************************** -->




















            </table>
        </div>
    </div>
</div>