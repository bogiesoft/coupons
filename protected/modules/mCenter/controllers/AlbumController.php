<?php
/**
 * 相册管理
 */
class AlbumController extends mCenterController
{
    /**
     * 相册管理
     */
    public function actionPhotoManagement()
    {
        $merchantId = Yii::app()->session['merchant_id'];        
        $ret        = new AlbumC();
        $rat        = $ret->PhotoManagement($merchantId);
        $list       = json_decode($rat,true);
        if($list['status'] == ERROR_NONE)
        {
            $list = $list['data'];
        } else {
        	echo $list['errMsg'];
            $list = '';
        }
        $this->render('photoManagement',array('list'=>$list));
    }
    
    /**
     * 修改相册管理名称
     */
    public function actionEditPhotoManagement()
    {
        $merchantId = Yii::app()->session['merchant_id'];
        $ret        = new AlbumC();        
        if(isset($_POST) && $_POST)
        {
            $id         = isset($_POST['groupid']) ? $_POST['groupid'] : '';
            $name       = isset($_POST['name']) ? $_POST['name'] : '';            
            $rat        = $ret->EditPhotoManagement($id, $name, $merchantId);
            $editname   = json_decode($rat,true);
            if($editname['status'] == ERROR_NONE)
            {
                $url = Yii::app()->createUrl('mCenter/album/photoManagement');
                echo "<script>alert('修改成功');window.location.href='$url'</script>";
            } else {
                $url = Yii::app()->createUrl('mCenter/album/photoManagement');
                echo "<script>alert('修改失败');window.location.href='$url'</script>";
            }    
        }
    }

    /**
     * 相册子类分组列表
     */
    public function actionPhotoSubclass()
    {
        $merchantId    = Yii::app()->session['merchant_id'];
        $id            = isset($_GET['groupid']) ? $_GET['groupid'] : '';
        $ret           = new AlbumC();
        $rot           = $ret->PhotoSubclass($merchantId,$id);
        $photosubclass = json_decode($rot,true);
        $album = isset($photosubclass['datas']) ? $photosubclass['datas'] : '';
        if($photosubclass['status'] == ERROR_NONE)
        {            
            $photosubclass = $photosubclass['data'];
        } else {            
            $photosubclass = '';            
        }  
        $albumId    = $id;        
        $rat  = $ret->PhotoGroup($merchantId,$albumId);
        $list = json_decode($rat,true);        
        if($list['status'] == ERROR_NONE)
        {
            $list = $list['data'];            
        } else {
            $list = '';
        }
        $this->render('photoSubclass',array('photosubclass'=>$photosubclass,'list'=>$list,'album'=>$album));
    }
    
    /**
     * 修改子类分组
     */
    public function actionEditPhotoSubclass()
    {
        $merchantId = Yii::app()->session['merchant_id'];
        if(isset($_POST) && $_POST)
        {
            $flag = 0;
//            if(empty($_POST['group_name']))
//            {
//                Yii::app()->user->setFlash('group_name','请输入分组名称');
//                $flag = 1;
//            }
            if($flag == 0)
            {
                $groupid = isset($_POST['groupid']) ? $_POST['groupid'] : '';
                $id   = isset($_POST['id']) ? $_POST['id'] : '';
                $name = isset($_POST['group_name']) ? $_POST['group_name'] : '';
                $ret  = new AlbumC();
                $rot  = $ret->EditGroup($merchantId,$id,$name);
                $group = json_decode($rot,true);
                if($group['status'] == ERROR_NONE)
                {
                    $url = Yii::app()->createUrl('mCenter/album/photoSubclass',array('groupid'=>$groupid));
                    echo "<script>alert('修改成功');window.location.href='$url'</script>";
                } else {
                    $url = Yii::app()->createUrl('mCenter/album/photoSubclass',array('groupid'=>$groupid));
                    echo "<script>alert('修改失败,请输入分组名称');window.location.href='$url'</script>";
                }
            }
        }
        
    }

    /**
     * 删除子类分组
     */
    public function actionDelPhotoSubclass()
    {
        $merchantId = Yii::app()->session['merchant_id']; 
        $groupid = isset($_GET['groupid']) ? $_GET['groupid'] : '';
        $id    = isset($_GET['id']) ? $_GET['id'] : '';                
        $ret   = new AlbumC();
        $rot   = $ret->DelGroup($merchantId,$id);
        $group = json_decode($rot,true);
        if($group['status'] == ERROR_NONE)
        {  
            $url = Yii::app()->createUrl('mCenter/album/photoSubclass',array('groupid'=>$groupid));
            echo "<script>alert('删除成功');window.location.href='$url';</script>";
        } else {
            $url = Yii::app()->createUrl('mCenter/album/photoSubclass',array('groupid'=>$groupid));
            echo "<script>alert('删除失败');window.location.href='$url';</script>";
        }
    }

    /**
     * 创建子类分组
     */
    public function actionAddGroup()
    {
        $merchantId = Yii::app()->session['merchant_id'];
        if(isset($_POST) && $_POST)
        {
            $flag = 0;
//            if(empty($_POST['group_name']))
//            {
//                Yii::app()->user->setFlash('group_name','请输入分组名称');
//                $flag = 1;
//            }
            if($flag == 0)
            {
                $id   = isset($_GET['groupid']) ? $_GET['groupid'] : '';
                $name = isset($_POST['group_name']) ? $_POST['group_name'] : '';
                $ret  = new AlbumC();
                $rot  = $ret->AddGroup($merchantId,$id,$name);
                $group = json_decode($rot,true);
                if($group['status'] == ERROR_NONE)
                {
                    $url = Yii::app()->createUrl('mCenter/album/photoSubclass',array('groupid'=>$id));
                    echo "<script>alert('添加成功');window.location.href='$url'</script>";
                } else {
                    $url = Yii::app()->createUrl('mCenter/album/photoSubclass',array('groupid'=>$id));
                    echo "<script>alert('添加失败,请输入分组名称');window.location.href='$url'</script>";
                }
            }
        }
        
    }
    
    
    /**
     * 添加照片
     */
    public function actionAddPhoto()
    {
        $merchantId = Yii::app()->session['merchant_id'];
        $albumId    = isset($_GET['album_id']) ? $_GET['album_id'] : '';        
        $ret  = new AlbumC();
        $rot  = $ret->PhotoGroup($merchantId,$albumId);
        $list = json_decode($rot,true);
        if($list['status'] == ERROR_NONE)
        {
            $list = $list['data'];
        } else {
            $list = '';
        }
        $img = '';
        $name = '';
        if(isset($_POST) && $_POST)
        {
            $flag = 0;
            if(empty($_POST['album_group_id']))
            {
                $url = Yii::app()->createUrl('mCenter/album/photoSubclass',array('groupid'=>$albumId));
                echo "<script>alert('请创建分组或者选择分组');window.location.href='$url'</script>";
                $flag = 1;
            }
            if(empty($_POST['imgsrc']))
            {
                $url = Yii::app()->createUrl('mCenter/album/photoSubclass',array('groupid'=>$albumId));
                echo "<script>alert('请上传图片');window.location.href='$url'</script>";
                $flag = 1;
            }
            if($flag == 0)
            {
                $albumgroupid = $_POST['album_group_id'];
                $count = count($_POST['imgsrc']);
                foreach ($_POST['imgsrc'] as $key => $value) 
                {
                    $img .= $value.';';
                } 
                foreach ($_POST['imgname'] as $k =>$v)
                {
                    $name .=$v.';';
                }
                $rnt = $ret->AddPhoto($albumgroupid,$img,$name);
                $photo = json_decode($rnt,true);
                if($photo['status'] == ERROR_NONE)
                {
                    $type=$_GET['type'];
                    if($type=='1')
                        $this->redirect(Yii::app()->createUrl('mCenter/album/photoSubclass',array('groupid'=>$albumId)));
                    else
                        $this->redirect(Yii::app()->createUrl('mCenter/album/groupPhotoList',array('album_group_id'=>$albumgroupid)));
                } else {
                    echo "<script>alert('添加失败');</script>";
                }
            }
        }

    }
    
    /**
     * 分组内图片列表
     */
    public function actionGroupPhotoList()
    {
        $ret = new AlbumC();
        $merchantId = Yii::app()->session['merchant_id'];
        $albumgroupid = isset($_GET['album_group_id']) ? $_GET['album_group_id'] : '';
        $rot = $ret->GroupPhotoList($albumgroupid);
        $list = json_decode($rot,true);
        $rs=json_decode($ret->getAlbumId($albumgroupid,$merchantId),true);
        $albumid=null;
        $move=array();
        if($list['status'] == ERROR_NONE&&$rs['status']==ERROR_NONE)
        {
            $list = $list['data'];
            $albumid=$rs['data'];
            $albumname=$rs['album_name'];
            $albumgroupname=$rs['albumgroup_name'];
            $reslut=json_decode($ret->getAlbumGroup($merchantId,$albumid,$albumgroupid),true);
            if($reslut['status']==ERROR_NONE)
            {
                $move=$reslut['data'];
            }
        } else {
            $list = '';
        }
        $this->render('groupPhotoList',array('list'=>$list,'albumgroupid'=>$albumgroupid,'albumid'=>$albumid,'move'=>$move,'albumgroup_name'=>$albumgroupname,'albumname'=>$albumname));
    }
    
    /**
     * 删除分组内图片
     */
    public function actionDelGroupPhoto()
    {
        $id  = isset($_GET['id']) ? $_GET['id'] : '';
        $photoid = isset($_GET['photo_id']) ? $_GET['photo_id'] : '';
        $ret = new AlbumC();
        $rot = $ret->DelGroupPhoto($id);
        $del = json_decode($rot,true);
        if($del['status'] == ERROR_NONE)
        {
            $url = Yii::app()->createUrl('mCenter/album/groupPhotoList',array('album_group_id'=>$photoid));
            echo "<script>alert('删除成功');window.location.href='$url';</script>";
        } else {
            $url = Yii::app()->createUrl('mCenter/album/groupPhotoList',array('album_group_id'=>$photoid));
            echo "<script>alert('删除失败');window.location.href='$url';</script>";
        }
    }

    /**
     * 删除选中的图片
     */
    public function actionDelSomeGroupPhoto()
    {
        $arr=array();
        $arr=isset($_GET['arr']) ? $_GET['arr'] : null;
        $photoid=isset($_GET['photo_id']) ? $_GET['photo_id'] : null;
        $ret = new AlbumC();
        if(!empty($arr)) {
            for($i=0;$i<count($arr);$i++) {
                $rs = json_decode($ret->DelGroupPhoto($arr[$i]), true);
                if ($rs['status'] != ERROR_NONE) {
//                $this->redirect('mCenter/album/groupPhotoList',array('album_group_id'=>$photoid));
                    echo json_encode('error');
                    break;
                }
            }
        }
        else
        {
            echo json_encode('error');
        }
//        $url = Yii::app()->createUrl('mCenter/album/groupPhotoList',array('album_group_id'=>$photoid));
        echo json_encode("success");
    }

    /**
     * 删除相册group
     */
    public function actionDelAllSubclass()
    {
        if(!empty($_GET['group_id']))
        {
            $album_id=$_GET['group_id'];
            $merchantId = Yii::app()->session['merchant_id'];
            $album=new AlbumC();
            $result=json_decode($album->delAlbumGroup($merchantId,$album_id),true);
            if($result['status']==ERROR_NONE)
            {
                echo json_encode('success');
            }else
            {
                echo json_encode('error');
            }
        }else
        {
            echo json_encode('error');
        }
    }

    public function actionAddPhotoInSub()
    {
        $merchantId = Yii::app()->session['merchant_id'];
        if(!empty($_GET['albumgroup_id']))
        {
            $albumgroup_id=$_GET['albumgroup_id'];

        }
    }

    /**
     * 移动图片
     */
    public function actionMovePhoto()
    {
        if(!empty($_GET['id'])&&!empty($_GET['arr_id'])&&!empty($_GET['this_id']))
        {
            $arr_id=array();
            $albumgroup_id=$_GET['id'];//移动到的ID
            $arr_id=$_GET['arr_id'];//要删除图片的ID
            $this_id=$_GET['this_id'];//要删除的图片的groupid
            $merchantId = Yii::app()->session['merchant_id'];
            $album=new AlbumC();
            $rs=json_decode($album->movePhoto($merchantId,$this_id,$arr_id,$albumgroup_id),true);
            if($rs['status']==ERROR_NONE)
            {
                echo json_encode('success');
            }else
            {
                echo json_encode('error');
            }
        }
        else
        {
            echo json_encode('error');
        }
    }
}

