<?php

                                if($pibi==0){ ?>
                                <tr>
                                    <td style="align:center;">
                                        <div id="s<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 1, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"><i class="bi bi-square-fill"></i></div>
                                    </td>

                                    <td style="align:center;">
                                        <div id="c<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 2, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"><i class="bi bi-circle-fill"></i></div>
                                    </td>

                                    <td style="align:center;">
                                        <div id="t<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 3, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"><i class="bi bi-triangle-fill"></i></div>
                                    </td>
                                    
                                    <td style="align:center;">
                                        <div id="b<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 0, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:gray; font-size:28px;"></div>
                                    </td>
                                    
                                    <td style="text-align:right;">
                                        <div style="width:15px; height:15px; border-radius:50%; position:relative; background:red;"></div>
                                    </td>
                                </tr>
                                <?php } else if($pibi==1){ ?>
                                <tr>
                                    <td style="align:center;">
                                        <div id="s<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 1, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid orange; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:white; background:orange; font-size:28px;"><i class="bi bi-square-fill"></i></div>
                                    </td>

                                    <td style="align:center;">
                                        <div id="c<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 2, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"><i class="bi bi-circle-fill"></i></div>
                                    </td>

                                    <td style="align:center;">
                                        <div id="t<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 3, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?> );"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"><i class="bi bi-triangle-fill"></i></div>
                                    </td>
                                    
                                    <td style="align:center;">
                                        <div id="b<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 0, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"></div>
                                    </td>
                                    
                                    <td style="align:right;">
                                        
                                    </td>
                                </tr>
                                <?php } else if($pibi==2){ ?>
                                <tr>
                                    <td style="align:center;">
                                        <div id="s<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 1, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"><i class="bi bi-square-fill"></i></div>
                                    </td>

                                    <td style="align:center;">
                                        <div id="c<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 2, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid darkcyan; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:white; background:darkcyan; font-size:28px;"><i class="bi bi-circle-fill"></i></div>
                                    </td>

                                    <td style="align:center;">
                                        <div id="t<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 3, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"><i class="bi bi-triangle-fill"></i></div>
                                    </td>
                                    
                                    <td style="align:center;">
                                        <div id="b<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 0, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"></div>
                                    </td>
                                    
                                    <td style="align:right;">
                                        
                                    </td>
                                </tr>
                                <?php } else if($pibi==3){ ?>
                                <tr>
                                    <td style="align:center;">
                                        <div id="s<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 1, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"><i class="bi bi-square-fill"></i></div>
                                    </td>

                                    <td style="align:center;">
                                        <div id="c<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 2, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"><i class="bi bi-circle-fill"></i></div>
                                    </td>

                                    <td style="align:center;">
                                        <div id="t<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 3, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid seagreen; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:white; background:seagreen; font-size:28px;"><i class="bi bi-triangle-fill"></i></div>
                                    </td>
                                    
                                    <td style="align:center;">
                                        <div id="b<?php echo $stid.$tid;?>" onclick="mentry(<?php echo $stid;?>, 0, <?php echo $rollno;?>, '<?php echo $tid;?>', <?php echo $acode;?>);"
                                            style="border:1px solid gray; border-radius:5px; height:50px; width:50px; padding-top:10px; text-align:center;
                                            color:lightgray; font-size:28px;"></div>
                                    </td>
                                    
                                    <td style="align:right;">
                                        
                                    </td>
                                </tr>
                                <?php } ?>