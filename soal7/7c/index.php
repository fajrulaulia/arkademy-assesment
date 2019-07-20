<?php include 'module.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="vendor/css/app.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="vendor/js/app.js"></script>
   
    <style>
    nav.navbar{
      box-shadow: 0px 0px 5px 0px;
      padding: 0px;
      margin: 0px;
    }
    nav.navbar a{
      color:black;
      font-weight: bolder;
      font-size: 2em;
      margin: 0px;
      padding: 0px;
    }

    .table *{
      text-align: center;
    }

    .table thead tr{
      background-color: #eee;
    }

    .container.mt-3 .btn-add{
      display: flex;
        justify-content: space-between;
    }
    .fa-edit{
      color:green;
    }
    .fa-trash{
      color:red;
    }
    
    </style>
</head>
<body>
    <nav class="navbar">
      <a class="navbar-brand" href="#"><img src="vendor/img/logo.svg" width="120" /> Bootcamp Arkademy</a>
    </nav>
    <div class="container mt-3">
      <button style="float: right" type="button" class="btn btn-warning btn-sm btn-add" data-toggle="modal" data-target="#formInput">Add Data</button>
      <br>
      <table class="table table-bordered mt-3">
        <thead>
          <tr>
            <th>Name</th>
            <th>Work</th>
            <th>Salary</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $result=$conn->query("select nama.id as id, nama.name as name, work.id as idwork, work.name as work, category.id as idcategory, category.salary as salary from nama
            inner join work on nama.id_work=work.id
            inner join category on nama.id_salary=category.id;");
            foreach($result as $item){
          ?>
          <tr>
            <td><?php print $item['name']; ?></td>
            <td><?php print $item['work']; ?></td>
            <td><?php print $item['salary']; ?></td>
            <td>
              <a class="btn btn-link btn-sm"  href="./?hapus=<?php print $item['id']; ?>"><span class="fa fa-fw fa-trash"></span></a>
              <button class="btn btn-link btn-sm" onclick="getNAMA(<?php print $item['id']; ?>,'<?php print $item['name']; ?>','<?php print $item['work']; ?>','<?php print $item['salary']; ?>')" data-toggle="modal" data-target="#formEdit" ><span class="fa fa-fw fa-edit"></span></button>
            </td>
          </tr>  
          <?php
            }
          ?>      
        </tbody>
      </table>
    </div>
</body>
</html>
<script>
function getNAMA(id,nama,idwork,idcategory){
  document.getElementById('nameid').value=nama;
  document.getElementById('workid').value=idwork;
  document.getElementById('salaryid').value=idcategory;
  document.getElementById('idname').value=id;
  document.getElementById('formmedit').action="./";
}
</script>

<div id="formEdit" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <form method="POST" id="formmedit">
            <div class="form-group">
                <input type="text" name="name" class="form-control" id="nameid" placeholder="Name">
                <input type="hidden" name="id" class="form-control" id="idname" placeholder="Name">
              </div>
              <div class="form-group">
              <input type="text" name="name" class="form-control" disabled id="workid" placeholder="Name">
              </div>
              <div class="form-group">
              <input type="text" name="name" class="form-control" disabled id="salaryid" placeholder="Name">
            </div>
          
          </div>
      <div class="modal-footer">
          <button type="submit" name="update" class="btn btn-warning" >edit</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php
  if(isset($_POST['update'])){
    extract($_POST);
    $stmt = $conn->query("update nama set name='$name' where id='$id'");
    echo "
    <script type='text/javascript'>
    
        location.assign('./');
</script>
    
    ";
  }
?>



<div id="formInput" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <form method="POST" action="./">
            <div class="form-group">
                <input type="text" name="name" class="form-control" id="usr" placeholder="Name">
              </div>
              <div class="form-group">
                <select class="form-control" name="work">
                  <?php
                    $result=$conn->query("select * from work");
                    foreach($result as $item){
                    ?>
                    <option value="<?php print $item['id']; ?>"><?php print $item['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                  <select class="form-control" name="salary">
                    <?php
                      $result=$conn->query("select * from category");
                      foreach($result as $item){
                      ?>
                      <option value="<?php print $item['id']; ?>"><?php print $item['salary']; ?></option>
                    <?php } ?>
                  </select>
                </div>
          
      </div>
      <div class="modal-footer">
          <button type="submit" name="simpan" class="btn btn-warning" >Add</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php
  if(isset($_POST['simpan'])){
    extract($_POST);
    $stmt = $conn->prepare("INSERT INTO nama(name, id_work, id_salary) VALUES(?, ?, ?)");
    $stmt->bind_param("sss", $name, $work, $salary);
    $stmt->execute();
    echo "
    <script type='text/javascript'>
    
        location.assign('./');
</script>
    
    ";
  }
?>



<?php
  if(isset($_GET['hapus'])){
    $stmt = $conn->prepare("DELETE FROM nama WHERE id=?");
    $stmt->bind_param("s",$_GET['hapus']);
    $stmt->execute();
    ?>
<div id="formHapus" class="modal show" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">   
        <div class="modal-body">
         <center> <br> <br> <br> <br>
            <svg width="110" height="110" viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <rect width="110" height="110" fill="url(#pattern0)"/>
              <defs>
              <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0" transform="scale(0.00195312)"/>
              </pattern>
              <image id="image0" width="512" height="512" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAgAAAAIACAYAAAD0eNT6AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAOSAAADkgBa28N/wAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAADAQSURBVHja7d19cNXVve/xGiADgkhHZ+RMqS11HI//VAu1pYVR8KnUXvGRYz1eEK+cO+qtrfZ49VQ9DHiQWtvrnfG2aNXWQi1RTIASRUDNE5AEkkAg4TGQByBPhCQE2HneWfe7tr+2EZOwd/bTb631XjOvmTMeK2Gt7299P9n791u/LymlvgTA36ZkrRotviK+KW4Uc8Uj4jnxilgh0kWm2CRyRIEoEeWiQtSIBtEqAqLXE/D+WYP371R4/5sS77+R4/03M70/Y4X3Zz7n/QxzvZ/pGjFJjGHNAP9jEoDkNvZR4hviJvGwWCreEZ+IXeKo16CVYdrFMVEqPhV/ES+KheJmcYVIpQYAAgBga4NPEV8TN4gFYrH3G3Su19yDBjb3WAl6ISFPrBRLxENipvi6njtqCCAAACY0+wnievET8YbYbuhv7376FGGHeEs87oWoL1NrAAEASFajHyH+Wdznfayd6X13TtNODP3JyQfe3Os1uFqvCbUJEACAWDf8r3qN5lVRJDpowr7T4a3Nq95afZXaBQgAQKS/3X/L+xg/zfttkwZr7icFad5afotPCQACANC/4Y8Xt3o3oOk778/QOK11xlvjJd6aj+caAAEAcOuu/GleE9ju+F34rgt6NbDEqwmeOgABALCs6U8UD3ofBzfT+DCIZq9GdK1M5NoBAQAwr+GP9B7HWyZ2ij6aGyLU59XOMq+WRnJtgQAA+LPpX+z95pYh2mhgiLE2r7Z0jV3MNQcCAJDcpn+ReECsF100KSRIl1dzuvYu4loEAQBITNMf6z3nvYbn8eGT8wfWeDU5lmsUBAAgtk1/jLhHrOZoXfhYwKvRe3gjIggAQHSP680Wq3g2H4aeObDKq2EeLwQBAAij8ev3yS/ibH1YpMar6Ulc4yAAAF88fneO91KdXhoGLNXr1fgcjiUGAQCuN/7JYqmopTnAMbVe7U9mLwABAK40/VHiXrGZA3qA0DWw2bsmRrFHgAAAGxv/pd73oI1s+sCAGr1r5FL2DBAAYEPjv1K8JtrZ4IGwtHvXzJXsISAAwMTGP0Os5W17QFRvK9TX0Az2FBAAYMLd/Pq7zEI2byCmCr1ri6cHQACA747nfVwcYaMG4qrSu9Y4dhgEACS18Y/3blpqYWMGEqrFu/bGsxeBAIBE/8b/jGhmIwaSqtm7FvlEAAQAxLXxjxZP8igf4MtHCPW1OZq9CgQAxLLxp4rHOLEPMOKEQX2tprJ3gQCAaBr/SLGQF/MARr6ASF+7I9nLQABAJI1fv4p3njjMRgoY7bB3LfNKYhAAcN7mP1OUsnECVtHX9Ez2OBAAMNib+TLYKAGrZfAGQhAA8LfGP04sE51sjoATOr1rfhx7IAEAbjb+C8QCUc+GCDip3tsDLmBPJADAneY/XRSxAQLw9oLp7I0EANjd+C8XaWx4AAag94bL2SsJALDvLX1PiQCbHIAhBLy9grcOEgBgQfO/VhSzsQGIgN4zrmUPJQDA3HP7XxI9bGYAhqHH20N4vwABAIYd5nOIDQxADBziECECAPzf+CeIN0UfmxaAGOrz9pYJ7LUEAPiv+d8t6tioAMSR3mPuZs8lAMAfjX+iWMPGBCCB9J4zkT2YAIDkNf85oonNCEAS6L1nDnsxAQCJbfwXitfZgAD4gN6LLmRvJgAg/s1/ijjApgPAR/SeNIU9mgCA+DT+FPG06GazAeBD3d4elcKeTQBA7Jr/JJHFBgPAAHqvmsTeTQBA9M1/rmhhUwFgEL1nzWUPJwBgeI1/nHibjQSAwfQeNo49nQCA8Jv/VWIfmwcAC+i97Cr2dgIAzt/87xRtbBoALKL3tDvZ4wkAGPwu/2Wc4w/A4vcJLOMpAQIAPt/8LxGb2CAAOEDvdZew9xMAaP6fHexTzaYAwCHVHBxEAHC9+S8QHWwGAByk974F9AICgGuNP1UsZwMAgNBemEpvIAC40PwvE/lc9ADwd3pPvIweQQCwuflfLaq42AHgC/TeeDW9ggBgY/OfJVq5yAFgUHqPnEXPIADY1PznT+EtfgAQDr1Xzqd3EABsaP6LuaABIGKL6SEEAJPv9F/JRQwAw7aSJwQIAKY1/wkim4sXAKKm99IJ9BYCgAnNf/IU3uQHALGk99TJ9BgCgJ+b/1TRyMUKADGn99ap9BoCgB+b/4wpvMYXAOJJ77Ez6DkEAD81/1tEgIsTAOJO77W30HsIAH5o/neITi5KAEgYvefeQQ8iACSz+d8vergYASDh9N57P72IAJCM5r9QBLkIASBp9B68kJ5EAEhk839C9HHxAUDS6b34CXoTASARzf95LjgA8J3n6VEEgHg2/19xkQGAb/2KXkUAoPkDACEABAA+9gcAvg4AASDyG/64oADALNwYSACI+lE/7vYHADOfDuARQQLAsA/54Tl/ADD7nAAOCyIARHy8Lyf8AYAdJwZybDABIOwX+3C2PwDYQ+/pvECIAHDeV/ryVj8AsI/e23mVMAFgwOY/dcpn75rmQgEAO+k9fio9jwDQv/lPFo1cHABgPb3XT6b3EQB0858g9nFRAIAz9J4/gQDgdvNPFdlcDADgHL33pxIA3A0AK7kIAMBZKwkAbjb/xRQ/ADhvMQHAreY/n6IHAHjmEwDcaP6zRDcFDwDw6J4wiwBgd/O/WrRS7ACAc+jecDUBwM7mf5moosgBAIPQPeIyAoB9j/vlU9wAgPPId+XxQFcCwHKKGgAQpuUEADua/wKKGQAQoQUEALOb/xTRQSEDACKke8cUAoCZzf8SUU0RAwCGSfeQSwgAZjX/FLGJ4gUAREn3khQCgDkBYBlFCwCIkWUEADOa/52ij4IFAMSI7il3EgD83fyvEm0UKwAgxnRvuYoA4M/mP07so0gBAHGie8w4AoD/AsDbFCcAIM7eJgD4q/nPpSgBAAkylwDgj+Y/SbRQkACABNE9ZxIBIPnP+2dRjACABMsy/XwA0wPA0xQhACBJniYAJO+c/24KEACQJLoHTSEAJLb5XygOUHwAgCTTvehCAkDiAsDrFB0AwCdeJwAkpvnPodgAAD4zhwAQ3+Y/UTRRaAAAn9G9aSIBIH4BYA1FBgDwqTUEgPg0/7spLgCAz91NAIht858g6igsAIDP6V41gQAQuwDwJkUFADDEmwSA2DT/maKPggIAGEL3rJkEgOia/2hxiGICABhG967RBIDhB4CXKCIAgKFeIgAMr/lfK3ooIACAoXQPu5YAEFnzHyGKKR4AgOF0LxtBAAg/ADxF0QAALPEUASC85n+5CFAwAABL6J52OQHg/AEgjWIBAFgmjQAwdPOfTpEAACw1nQAwcPO/QBRRIAAAS+kedwEB4IsBYAHFAQCw3AICwOeb/zhRT2EAACyne904AsA/AsAyigIA4IhlBIDPmv9k0UlBAAAcoXveZAJA1qoMigEA4JgMpwPAlM9e9UshAABcNNPJACB/8RRRSgEAAByle2CKiwFgHosPAHDcPKcCgPyFR4rDLDwAwHG6F450KQAsZNEBAAhZ6EQAkL9oqqhhwQEACNE9MdWFAPAYiw0AwOc8ZnUAkL/gaFHLQgMA8Dm6N462OQA8ySIDADCgJ60MAPIXGysaWWAAAAake+RYGwPAMywuAABDesaqACB/ofGimYUFAGBIuleOtykALGJRAQAIyyIrAoD33X8LCwoAQFhaEnEvQCICwOMsJgAAEXnc6AAgf4ERopKFBAAgIrp3jjA5ANzLIgIAMCz3mhwACllAAACGpdDIACA/+AwWDwCAqMwwMQCsZeEAAIjKWqMCgPzAV4ogCwcAQFR0L73SpADwGosGAEBMvGZEAJAf9FLRzoIBABATuqdeakIA4NhfAABia5GvA4D8gKOm8MpfAABiTffWUX4OABz8AwA+8t2cd9XNW9eoOQXr1U1bMtS3s9OYF3Pd6+cAsJkFAoDEukUa/KO7stRvKkrU2rrDak9bk2rqaledwV517gj29alT3Z2qKtCm/lp3RP18T576Xs57zKMZNvsyAMgPNln0sUAAEB/T5Lf5h3d+rH55sEi9f/yQKmltVG3dXSraoYNCdtMxNXf7h8yzv+keO9mPAWApiwMAsTNVPFC0Ub16uFQVttQP+Bt9LEdPX1D9vqos9LUB8+9bS30VAKZ89ta/WhYGAKJ3344N6u2avaq+I6CSMY6cPaXu3/ERa+FPuteO8FMAmMOiAMDw3Z6/Xv32yG51WJqvH0ZLd6e6vWA9a+NPc/wUADJZEACIjL4j/5nyrar01Anlx1EdaFMzt6SzVv6T6YsAID/IJNHLggBAeGbkrg7dsV/bcVb5fegbDbknwHd0z53khwDAyX8AEIbZ29aqP9XsU6d7upRJY4X8zKyf7yxKagCQHyBF1LAQADC4H+/YoD6orwrdZW/i0E8f6LMGWEtf0b03JZkBYDaLAAADm1+8KfT4ng0j7dhB1tR/ZiczAKxiAQDg8/5b/l/VxsZq1afsGV3BYOgrDNbXV1YlJQDIHzxGnGEBAOAzN+Slq5U1+0PN0sahTx9knX1F9+AxyQgA9zD5ALBKfSf7XfXyoeLQGfs2j24JNtfnvc+a+8s9yQgAq5l4AK57qmyLqmk/rVwZ+twC1t1XVic0AMgfOFYEmHgArppXvEnt8ukBPvEcmfWVrL+/6F48NpEB4D4mHYCLbstfZ90NfpEeEaxPMKQWfOW+RAaANUw4AJfoN/Pp1/AGenuU62Ne8UZqwl/WJCQAyB90kehgwgG4Yk7BelXc2qgYn43n9+ZTF/6ie/JFiQgADzDZAFygP+r+PxUlqqO3l67fb/zuyG7qw38eSEQAWM9EA7Dd3YUfqN2nmuj2A4w1tYepEf9ZH9cAIH/AxaKLiQZg82/9rx4uVV1BfusfbBQ011Mr/qN788XxDAAPMskAbDV3+4dq7+lmOvx5hr4fgnrxpQfjGQAymGAAttEn+S2v3BM66Y5x/pF38jh1408ZcQkA8h8eKdqYYAA2uX/HR+rgmVa6egTjo4ZqasefdI8eGY8AcD2TC8Cm3/rfqi5XvX19dPQIR0ZtBTXkX9fHIwAsY2IB2OCH29apPW3c4c9jgFZaFo8AsJOJBWC6x0qzVKvlb+2L91i48xNqyb92xjQAyH9wouhjYgGY/Hjf65V7VJCP/KMaXcGgmpbzHjXlX7pXT4xlAODxPwDGunFLhspvrqN7x2DsbD1BTVnyOGC4ASCNCQVgovnFm1RDZ4DOHaPxm4oS6sr/0mISAOQ/lCKamVAApnn5ULHq6ePZ/lgNfe/E9NzV1Jb/6Z6dEosAMI3JBGCSGdKkNjXW0LFjPJZXcve/QabFIgAsYSIBmOLe7R+qqkAb3TrG40xPt7ohL50aM8eSWASA7UwkABM8u3ebau/toVvHYfyifBs1ZpbtUQUA+Q+MF0EmEoCffTfnXbX6+CG6dJyGnlvqzDi6d4+PJgD8gEkE4Gc/yv+rKm/jDX7xGvtON6tpErCoNSP9IJoA8AITCMCvHt+drdq6u+jScWz+t25dS62Z64VoAkAWEwjAj16p2Kk40y9+I7vpmPp+Lif+GS5rWAFgymev/w0wgQD8RB/pu+rYATp0nIYOVStr9ofmmXoznu7hI4cTAK5j8gD4iT6D/tMTR+nScRqFLfXqgaKN1JpdrhtOAHiCiQPgF7O2ZKjSU7zCNx5j7+lm9eiuLOrMTk8MJwCkM3EA/OD2gvWqOnCaTh3Dod+KmNN0XD2y61NqzG7pwwkA9UwcgGSbV7xRNXd10LFjeKLfO0cPhEIV9eWE+ogCgPwPrmDSACTbz3bnqo7eXrp2DIY+HvmXB4tC70mgtpxzRSQBYD4TBiCZlh3cEfqYmhHdHf1bT9aq/1WaraZSUy6bH0kAeIMJA5AMulH9sXov3TuKEejtVmnHDqo7CzKpKWhvRBIAypgwAImmz/Tf0FBFBx/mqO8IqLdr9oaOR6ae0E9ZWAFA/sVU0cOEAUik6/PeVztaGujiwxh72prUv5flcXgPBqN7emo4AeAaJgtAIv1w2zp1+OwpOnmEo7i1kcf4EK5rwgkA85goAIly344NqrGznW4ewdjWXKceKvmY+kEk5oUTAF5mogAkgj597mxPNx09zDv6s04c46heDNfL4QSAjUwUgHhbtK9A9fQF6exhnNj3UUO1mrv9Q+oG0dgYTgCoY6IAxNPyyt109jBGSWtj6CsSagYxUDdkAJB/4RImCUA86cfUGEOPhs6A+o/yrdQLYu2SoQLALCYIQLz8qWYf3X2I0RXsVW9Wlavv575HvSAeZg0VAH7KBAGIhxU0/yFHdtMxdXs+L+hBXP10qADwFhMEINb+fHQ/HX6QcaKzXf10dw51gkR4a6gAsIMJAkDzT8z4oL5KzcxLp06QKDsGDADy/0gRASYIQKzo984zvjhOdnWoJ/fkUiNINN3jUwYKAF9jcgDEyl9o/gMO/Uz/zC381o+k+dpAAeAGJgZALKw6RvM/dzTLb/1PlW2hPpBsNwwUABYwMQCipd9Dz/j8+PjEUXXjlgzqA36wYKAAsJiJAUDzj+1z/f91YDu1AT9ZPFAAWMHEABiud2n+nxvVgdMc4ws/WjFQAMhlYgAMx+rjh+j459zoNyN3NbUBP8odKAAcZWIARGKqeJ/mz0f+MMnRzwUA+QejRJCJcdePd2xQz+7dppZX7lGZ9ZVq16kTak9bkypsrg+9g1z/s7eqy0Pvb+eccvyj+VfQ9fnIH2bRvX5U/wDwDSbFPXcVfqDeqCpTNe2nI9rouoNBtbP1hPrdkd1qFnc2O9v802tp/nzkD0N9o38AuIkJccfDOz9WZW0nY7Lxtff2qJU1+9UtW9cwtw41/wyaPx/5w2Q39Q8ADzMh9rstf53a3FgTt41QPwJ2fd77zLXlzX9N7WE6v4yGzoC6f8dH1AVM9HD/ALCUCbHbbypKVKc06XiPpq52TjuzuPmvraP566Hvj7mZT71grqX9A8A7TIidvp2dlpTns/WNgz/YtpY1oPlbNzY0VKlpOe9SFzDZO/0DwCdMiH2+l/Oeym46lrSN8kxPt1p6YAdrYUHzX1d3xPnG3yf0ja/UBCzwSf8AsIsJsct35TcUfae+H8bWk7V8XGow3uqnVEdvL19twSa7+gcADgGyjN+OZW3t7lQ/35PH2hjmt/IbLzf7cbMf7DwM6G8BIMCE2OM/yrf6djPV3yPzvLQZ9ONtro/ytpPq1q3cywLrBEIBQP6P0UyGPe4u/EAFent8vakebz+jHirZzHr5mP64O9jX53Tz39hYrablcOolrDVaB4CvMBH2KGiuN2Jz1c3lzapy9Z1s7qb2m/+561PVFQw6fbPfa5V7qAXY7is6AHyTibDDvOJNxm22e083h44kZv384V+LPlJne7odPtkvqJ4u30otwAXf1AHgRibCDrlNx43cdPUBRb88WMQaJtkdBZmquavD2eYf6O0OffpBLcARN+oAMJeJMJ9+m5/p39hua67jccEk0Te61Xacdbb5t3R3hj79oBbgkLk6ADzCRJgvzWeP/Q13nOhs5wbBBLshL11VnG11tvnXSfC5qzCTWoBrHtEB4DkmwnyHz56yZkPu6QuqXx0qZl0TdFpk6akTzjZ/fd3M5shquOk5HQBeYSLMdtOWDGXjA1v6zPXv5/IYVrxcl52m8k4ed7b56xf6zNySTi3AVa/oALCCiTDb0z4++CfacehMa+jmNNY5tvT5/pn1lc42//zmOjWdA6ngthU6AKQzEWb789H9Vm/Wp3u61BN7cllraiZmB/xw/gSwKl0HgEwmwmx/deBNbforDn1wkH69MWsenVcPlzrb/FcfP0QNAZ/J1AFgExNhto8ba5z66HbWlgzWfZiW7C90tvn/vqqMGgD+YZMOADlMhNn08/OuPbb1QNFG1j5C+m2MLp7vr//GPFUCfEGODgAFTITZdjn4GFdXsDf02yzrH55/2/lJaM5cG/qR0mf3bqMGgC8q0AGghIkwW9aJY85+rLum9rCalsMNXUO5d/uH6oyD5/vr5v8kN48CgynRAaCciTDbyhp37+jWQ79Q6Lb8ddTCAPTRyi4e8aub/7+X5VEDwODKdQCoYCLMtuzgDuX6aO3uVI/uyqIe+tHvst99qsm5Wujt61P/u2wLNQAMrUIHgBomwmy68TFU6AY3HYaoic8O+tHPu7tYA7zOFwhLjQ4ADUyE2WbmpYc+8mR8Nv5Usy/UAF2uiTerypxs/r8o54Y/IEwNOgC0MhHm23Kyls7fb2xqrHH25sDn9+Y72fy52x+ISKsOAAEmgk3fxqEfj3TtZS//o+Rj1RUMOtf8/3NfPvsAEJmADgC9TIT5ZuS+7+Rz3ucb1YHT6vaC9U7UwBz5e+qbIV1r/ov2FbAHAJHrJQBYJKO2go4/wGiRpji/eJPVa39DXrqqCrQ5ta76hD8OgwKiCwB8BWDRM99nHTzwJZzRGewNHYVr47pfl52mtrc0ONf8X9i/neseiPIrAG4CtMj/PbyTbj/Ex8W/PFhk3ZqvrTvsXPN/8QCPewKxuAmQxwAt8t2cd9XR9tN0+yGGfh0ugc/csczCEAck6zFADgKyjH5TXntvD51+iPFWdbnx6+zi2/14qx8Q24OAOArYQo/vznHy1a+RjLRjB409MOhfiz5SHb1uPfXx60MlXNtA7FTwMiCLLT3AOwLON9bVHVHfzk4zal1nb1urTnS2O7VOyyt3c00DsVXO64At95uKktDLURiDD31q4HeyzTg1cHruanXgTItzn9RwLQMxF3odcAETYbeHd37s3G+MkY68k8dDb8/z+1q69oKfjxqqnX+vAxAnBToA5DARbpwR4Nqz4pGOHTI/+jdsP3+a49LIb64z5pMZwEA5OgBsYiLcoL/r1o/AdQd5c+BgY/epptCxyn78FMelr3LK2k76OowBFtikA0AmE+GW+3ZsUAfPtNLtBxnFrY3qez76OuDWrWvVya4OZ+a/MtCmZm3J4FoF4itTB4B0JsLNA4P+WL2XRwUHGfr1yn74+Fn/DKWnTjgz7w2dAfXDbeu4RoH4S9cBYAUT4a4FJZs5OXCIpwOS/YigvgPelXGqu1PdXfgB1yWQGCt0AHiFiXDb93PfU6uPH6LjDzDW1B5O2ro8u3ebM/OsT66cZ/kbGwGfeUUHgOeYCGiPlWapRh4X/ML489H9CV+Lf9m+wZmT/nr6gqHa4xoEEuo5HQAeYSLwN/q98hsaquj654xEnkR3fd77znwto+9A+UX5Nq49IPEe0QFgLhOBcz1dtkW1dnfS+fuNlxPwIhp96E1u03Fn5pSX+wBJM1cHgBuZCAx2eJBLzSic31YX7SuI65zrTxpcGW9WlXGdAclzow4A1zARGIp+qVAXhweFhn5s8qmyLXG7B8OVxzLTayu4toDkukYHgElMBM7nvxdtVHUdZ0kAMvRJirG+ae1H+X8NPQbnwvj4xFHj3sAIWGiSDgBjmAiEQ5/OVtBcTwKQoe/Qf6hkc8wOZdp3utmJedPvW5iWw/n+gA+M+ZJckzoEtDMZCIf+ze2t6nLF+YFKtfV0qbticHCNfszQhaFDjh/fswA4SD/v/aW/BYBjTAgi8cSeXHWmp9v5EKAf14vm3PpHd2U5EaaOtZ9RN3G+P+AXx/oHgFImBJG6oyBTHeKlQmpn64nQx/jD+UrlhAMHL+mgyBG/gK+U9g8AnzIhGA59jPCHHBykMusrI5677KZj1s+LfoUxp/wBvvNp/wDwFyYE0XjpYFHoSFeXx++OhH9a4H8d2O7EnOi64PoAfOcv/QPAi0wIoqXfLOjyuwT0d/nPlG897zzdVZjpxDn/7x0/xHUB+NOL/QPAQiYEsTo9sLi10dkQ0BnsVfOHeKvdd7LdeORPPy7Ks/6Aby3sHwBuZkIQy0cF3zl6wNkQ0NzVoW7LXzfg3Lxds9f6v39loC30QiOuBcC3bu4fAK5gQhBrr1TsdPa8gIqzrV945v3fdn5i/VG/+jTD2wvWU/+Av13RPwCkiiCTglh7bm++szcHbj1Z9/ePwWfmpauGzoDVf199RPJCCTnUPeBruten/j0AKA4DQhz9pDRbtff2OBkC3j12MDQHHzfWWP93Xby/kHoHDDkE6NwAkMfEIF7mFW9y5mU3544cB16pvKJmH3UOmCFvoACwkolBPOlz8+s7Aoph19AHGnHHP2CMlQMFgCVMDOJt9ra16vDZU3RNS8bBM61qeu5qahswx5KBAsBDTAwSQd8QV3rqBN3T8HGyq0P9cNs6ahowy0MDBYCZTAwS5Xs576lcB74bt3V0BXtD93VQy4BxZg4UAL7OxCDRBwatqztCNzVw/KJ8GzUMmOnrAwWAFNHO5CDR3q7eS0c1aOhTHqlbwEi6x6d8IQB4IaCICUIy/PpQibOnBpo0Sk81hd5nQM0CRirq3/PPDQB/YIKQLM/u3eb8K4X9PFq6O0NPcVCrgLH+MFQA+BkThGR6rDQrdIMZw19Dv8Pg0V1Z1Chgtp8NFQBuZIKQbD/fk2f9S3NMG8sr91CbgPluHCoAXMoEwQ9e2L+druuToV9qNJWaBGxw6aABwAsBdUwS/OD/HSml+yZ56KObZ23JoB4B89Wd2+8HCgAbmSj4xapjB+jCSRr69b4c9gNYY2M4AeDXTBT8Qn/0/FFDNd04CeOlg0XUIGCPX4cTAOYxUfAT/dx5fnMdHTmBY2NjNbUH2GVeOAHgGiYKfqPfOFfWdpLOnIBRGWhTM3jDH2Cba8IJAKmih8mC3+ib0aqkOTHiN9p7e9S92z+k3gC76J6eet4A4IWAMiYMfnRb/jrV0BmgU8dpPLc3nzoD7FM2UK8fLAC8wYTBr/RvqG3dXXTrGA99syX1BVjpjUgCwHwmDH62oGSz6ujlyOBYjcbOdjUzL53aAuw0P5IAcAUTBr97fHeO6uXI4KiHnkHO+QesdkXYAcALAfVMGvzuP/fl8xrhKEfasYPUEmCv+sH6/FABIJ2JgwleqdhJF4/ikb/v5bxHHQH2Sh9OAHiCiYMpVtTso5tHOHr6guqBoo3UD2C3J4YTAK5j4mAKfWRw3snjdPUIxvLK3dQOYL/rhhMARooAkwdTXJ/3vqoOnKazhzH2tDWpb2enUTeA3XQPHxlxAPBCQBYTCJPcU/iBCvR20+HPc9rfHQWZ1Atgv6yhevz5AsALTCBM8/M9eTwZMMR48cAO6gRwwwvRBIAfMIEw0ZtVZXT6Acb2lgbqA3DHD6IJAONFkEkENwWaP7qCQXVXIR/9A47QvXv8sAOAFwK2M5HgpkDzxxtVZdQF4I7t5+vv4QSAJUwkuCnQ7HG0/bSaxoE/gEuWxCIATGMiwU2BZo/HSjnrH3DMtFgEgBTRzGSCmwLNHJsaa6gBwC26Z6dEHQC8EJDGhML0mwK3nKx1rvmf7elWt25dSw0AbkkLp7eHGwAeZELBTYHmjZcPFbP2gHsejGUAmCj6mFRwU6A54+CZVo77Bdyje/XEmAUALwTsZGJhg38vc+OmwEd3ceMf4KCd4fb1SALAMiYWtvhj9V6rm/+25jrWGXDTsngEgOuZWNjiuuy00BvxbBzBvj71L9s3sM6Am66PRwDQrwduY3Jhi9sL1ofukrdtrKs7wvoCbtI9emTMA4AXAjKYYNjkub35VjX/jt5eNXsbj/0BjsqIpKdHGgB4HBDW+bChypoA8Oej+1lTgMf/4hIALhZdTDJsMiP3fXW8/Yzxzb+3r0/9cNs61hRwk+7NF8ctAHghYD0TDdvML94UaqAmj48aqllLwF3rI+3nwwkADzDRsNFvj+w2OgDcv+Mj1hFw1wOJCAAXiQ4mG7bRp+YVtzYa2fyLWhpYQ8BduidfFPcA4IWANUw4bKS/Q2/r7jIuAOhXHrN+gLPWDKeXDzcA3MeEw1b6qGCTRkNngDP/Abfdl8gAMFYEmHTYKr22wpgA8FrlHtYMcJfuxWMTFgC8ELCaiYetvp/7nqoMtBnx6N+tWzn4B3DY6uH28WgCwD1MPGz24x0bVFcw6OsA8MmJo6wV4LZ7khEAxogzTD5s9puKEl8HgEd2fco6Ae7SPXhMwgOAFwJWsQCw2VSx9WQdv/0D8KNV0fTwaAPAbBYAtpu1JUMdbT/tq+ZfcbZVTc9dzfoAbpudzACQImpYBNjuzoJM35wPoH8O/Spj1gVwmu69KUkLAF4IWMRCwAUP7/w46TcFBvv61KO7slgPAIui7d+xCACTRC+LARc8u3dbUgPAKxU7WQcAuudOSnoA8EJAJgsCVyw9sEN1BXsT2vg7envVSweLmH8AWmYsenesAsAcFgQuuafwA7X/dEtCmn95W7O6S/485h2AZ46fAsAIUcuiwCXfyX5X/aG6PPS9fLxO+ft9VZm6jnP+AfyD7rUjfBMAvBCwlIWBix4q2awKm+tjGgQOnGlR84o3Mb8AzrU0Vn07lgFgsuhjceCq2/LXqdcr96jajrPDfrZfv9jnHj7uBzAw3WMn+y4AeCFgMwsE1+nTA/URvX+s3qs2NlarPW1N6mRXh+o753G+lu7O0Pf7vzuym+/4AYRjcyx7dqwDwL0sEDCwaTnvqdvz16sbt2Sob/O9PoDI3evnADBKNLJIAADElO6to3wbADgZEACAuFgU634djwBwqWhnsQAAiAndUy/1fQDwQsBrLBgAADHxWjx6dbwCwJUiyKIBABAV3UuvNCYAeCFgLQsHAEBU1sarT8czAMxg4QAAiMoM4wKAFwIKWTwAAIalMJ49Ot4BgIOBAAAYnntNDgD6LYGVLCIAABHRvXOEsQHACwGPs5AAAETk8Xj350QEgLGihcUEACAsumeONT4AcDwwAAARWZSI3pyoADBeNLOoAAAMSffK8dYEAC8EPMPCAgAwpGcS1ZcTGQD0vQC8KhgAgIE1JuK7/4QHAC8EPMkCAwAwoCcT2ZMTHQBGi1oWGQCAz9G9cbS1AcALAY+x0AAAfM5jie7HyQgAqaKGxQYAIET3xFTrA4AXAhay4AAAhCxMRi9OVgAYKQ6z6AAAx+leONKZAOCFgHksPADAcfOS1YeTGQBSRCmLDwBwlO6BKc4FAC8EzKQAAACOmpnMHpzUAOCFgAyKAADgmIxk918/BIDJopNiAAA4Qve8yc4HAC8ELKMgAACOWOaH3uuXADBO1FMUAADL6V43jgDw+RCwgMIAAFhugV/6rp8CwAWiiOIAAFhK97gLCAADh4DpFAgAwFLT/dRzfRUAvBCQRpEAACyT5rd+68cAcLkIUCwAAEvonnY5ASC8EPAUBQMAsMRTfuy1fg0AI0QxRQMAMJzuZSMIAJGFgGtFD8UDADCU7mHX+rXP+jYAeCHgJQoIAGCol/zcY/0eAEaLQxQRAMAwuneNJgBE/8rgPooJAGAI3bNm+r2/+j4AeCHgTQoKAGCIN03oraYEgAmijqICAPic7lUTCACxDQF3U1gAAJ+725S+akwA8ELAGooLAOBTa0zqqaYFgImiiSIDAPiM7k0TCQDxDQFzKDQAgM/MMa2fGhcAvBDwOsUGAPCJ103spaYGgAvFAYoOAJBkuhddSABIbAiYIropPgBAkugeNMXUPmpsAPBCwNMUIAAgSZ42uYeaHgBSRBZFCABIMN17UggAyQ0Bk0QLxQgASBDdcyaZ3j+NDwBeCJhLQQIAEmSuDb3TigDghYC3KUoAQJy9bUvftCkAjBP7KE4AQJzoHjOOAODPEHCVaKNIAQAxpnvLVTb1TKsCgBcC7hR9FCsAIEZ0T7nTtn5pXQDwQsAyChYAECPLbOyVtgYAfT7AJooWABClTaY/7+9UAPBCwCWimuIFAAyT7iGX2NonrQ0A/d4X0EERAwAipHvHFJt7pNUBwAsBCyhkAECEFtjeH60PAF4IWE4xAwDCtNyF3uhKAEgV+RQ1AOA8dK9IJQDYFQIuE1UUNwBgELpHXOZKX3QmAHgh4GrRSpEDAM6he8PVLvVEpwKAFwJmiW6KHQDg0T1hlmv90LkA4IWA+RQ8AMAz38Ve6GQA8ELAYooeAJy32NU+6GwA8ELASoofAJy10uUe6HoA0I8HZnMRAIBzsl153I8AMHgImCD2cTEAgDP0nj/B9f7nfADwQsBk0chFAQDW03v9ZHofAaB/CJgq2rg4AMBaeo+fSs8jAAwUAmaIABcJAFhH7+0z6HUEgKFCwC2ik4sFAKyh9/Rb6HEEgHBCwB2ih4sGAIyn9/I76G0EgEhCwP0iyMUDAMbSe/j99DQCwHBCwELRx0UEAMbRe/dCehkBIJoQ8AQXEgAY5wl6GAEgFiHgeS4mADDG8/QuAkAsQ8CvuKgAwPd+Rc8iABACAIDmDwIAXwcAAB/7gwAQ/Y2BPB0AAP64258b/ggACX9EkHMCACC5z/nzqB8BIGmHBXFiIAAk54Q/DvkhACT92GDeHQAAidPJ8b4EAD+9QIi3CAJA/Om9lhf7EAB89yrhNi5OAIgbvcfySl8CgC9DwFTRyEUKADGn99ap9BoCgJ9DwGSxj4sVAGJG76mT6TEEABNCwASRzUULAFHTe+kEegsBwKQQkCpWcvECwLDpPTSVnkIAMDUILOYiBoCILaaHEABsCAHzRTcXNACcl94r59M7CAA2hYBZopWLGwAGpffIWfQMAoCNIeBqUcVFDgBfoPfGq+kVBACbQ8BlIp+LHQD+Tu+Jl9EjCACuPCHwOy56AAjthdzpTwBw8ubAdjYAAA5q52Y/AoDrIeBaUclmAMAhes+7lh5AACAEZK36stjApgDAAXqv+zJ7PwEA/wgBKWKJ6GODAGChPm+PS2HPJwBg4CDwI84LAGDh8/0/Yo8nAOD8IeAKsZtNA4AF9F52BXs7AQDhh4Ax4jU2DwAG03vYGPZ0AgCGFwTmiCY2EgAG0XvWHPZwAgCiDwH/JDazqQAwgN6r/om9mwCA2IWAC8TPRRcbDAAf6vL2qAvYswkAiE8QuEbsY7MB4CN6T7qGPZoAgMTcILicTQeADyznRj8CABIfBG4XJ9iAACSB3ntuZy8mACB5IWCiWMNmBCCB9J4zkT2YAAB/BIG7RR0bE4A40nvM3ey5BAD4LwRMEG/yPgEAcTjHX+8tE9hrCQDwdxCYKQ6xaQGIAb2XzGRvJQDAnBAwWrwketjAAAxDj7eHjGZPJQDAzCBwrShmMwMQAb1nXMseSgCA+SFghHhKBNjYAAwh4O0VI9g7CQCwKwhcLtLY5AAMQO8Nl7NXEgBgdxCYLorY8AB4e8F09kYCANx6udACUc8GCDip3tsDeHkPAQCOBoFxYpnoZEMEnNDpXfPj2AMJAIAOApNFBpsjYDV9jU9mzwMBAIMdIlTKRglYpZTDfEAAQDghIEXME4fZOAGjHfau5RT2NhAAEEkQGCkWiho2UsAoNd61O5K9DAQARBMEUsVjopaNFfC1Wu9aTWXvAgEAsX6/wJOikY0W8JVG79rk3H4QABDXIDBWPCOa2XiBpGr2rsWx7E0gACCRQWC8WCRa2IiBhGrxrr3x7EUgACDZnwg8Lo6wMQNxVelda/zGDwIAfPfWwXtFIRs1EFOF3rXFW/pAAIDvw8AMsVYE2byBYQl619AM9hQQAGBiELhSvCba2dCBsLR718yV7CEgAMCGIHCpd9MSjxACgz/Kp6+RS9kzQACAjUFglPdd5mbRx6YPx/V514K+JkaxR4AAAJfeQLiUEwbh6Il9S3kzHwgA4OmBrFVzRKbopTnAUr1ejc/hbn4QAIAvhoFJ3vegvIAINr2YR9f0JK5xEACA8wcB/Uri2WKVOEMTgWHOeLU7m1fxggAADD8MjBH3iNUiQHOBTwW8GtW1OoZrFwQAIPbHDt8n1ogOmg6SrMOrxfs4nhcEACBxYeAi8YBYL7poRkiQLq/mdO1dxLUIAgCQ3DBwsXhQZIg2mhRirM2rLV1jF3PNgQAA+DMMjBTXi2ViJwcOYZgH9Oz0akjX0kiuLRAAAPMCwUTvN7c00UxzwyCavRrRtTKRawcEAMC+xwuniSViO28rdP5te9u9WpjG43ogAABuBYLx4lavCXzCmQPWP5v/ibfWes3Hcw2AAACg/7HE3xI/8T4OPkrjNNZRbw1/4q0px+8CBAAgolDwVe8571dFEecP+PZ5/CJvjfRafZXaBQgAQDw+Jfhnr9G86L3ghXcXJPY3+w+8uddrcDW/3QMEACCZwWCC98iY/sj5De8GM44uHr52sUO8JR4XN4gvU2sAAQAw5amDr3nNa4FYLFaIXO832aDjd+EfE3lipXdz3kNipvg6d+UDBADA5oAwSnxD3CQeFkvFO97d6ru8kBAw9Ld33dxLxafiL95H9gvFzeIKkUoNAAQAAEMHhdHiK+Kb4kYxVzwinhOveJ8qpHv3I2wSOaJAlIhyUeHdp9AgWr1Q0esJeP+swft3Krz/TYn338jx/puZ3p+xwvszn/N+hrnez3SNfuc9b8IDzPD/AekM1zb1PY5+AAAAAElFTkSuQmCC"/>
              </defs>
              </svg>
              <br> <br> <br> <br><strong>Data Berhasil Dihapus</strong>
         </center>
        </div>
      </div>
  </div>
</div>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#formHapus').modal('show');
        location.assign("./");
    });
</script>

<?php
  }
?>
