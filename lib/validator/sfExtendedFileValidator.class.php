<?php
/*
 * This file is part of the sfExtendedFileValidatorPlugin package.
 *
 * (c) 2008 Vincent Lemaire <symfony@vincentlemaire.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class sfExtendedFileValidator extends sfFileValidator
{
  /**
   * Method called to validate file(s)
   *
   * @param array $value
   * @param array $error
   * @return bool
   */
  public function execute(&$value, &$error)
  {
    $parent = parent::execute($value, $error);

    if (!$parent)
    {
      return false;
    }
    
    /*
     * $imgData[0] retrieve width
     * $imgData[1] retrieve height
     */
    $imgData = @getimagesize($value['tmp_name']);
    
    $min_width = $this->getParameter('min_width');

    if ($min_width !== null && $min_width > $imgData[0])
    {
      $error = $this->getParameter('min_width_error');

      return false;
    }

    $max_width = $this->getParameter('max_width');
    if ($max_width !== null && $max_width < $imgData[0])
    {
      $error = $this->getParameter('max_width_error');

      return false;
    }

    $min_height = $this->getParameter('min_height');

    if ($min_height !== null && $min_height > $imgData[1])
    {
      $error = $this->getParameter('min_height_error');

      return false;
    }

    $max_height = $this->getParameter('max_height');

    if ($max_height !== null && $max_height < $imgData[1])
    {
      $error = $this->getParameter('max_height_error');

      return false;
    }
    
    $exact_width = $this->getParameter('exact_width');

    if ($exact_width !== null && $exact_width != $imgData[0])
    {
      $error = $this->getParameter('exact_width_error');

      return false;
    }

    $exact_height = $this->getParameter('exact_height');

    if ($exact_height !== null && $exact_height != $imgData[1])
    {
      $error = $this->getParameter('exact_height_error');

      return false;
    }

    $aspect = $this->getParameter('aspect');

    if ($aspect !== null && ( is_float($aspect) || is_int($aspect) ) && $aspect != $imgData[0]/$imgData[1])
    {
      $error = $this->getParameter('aspect_error');

      return false;
    }
    
     // If we want to resave jpegs to noninterlaced (unfortunately this cannot be checked), resave the image using imageinterlace(0) and jpeg quality 90
     // Todo: Check if the image is noninterlaced before resaving
    if ( $imgData[2]==IMAGETYPE_JPEG && $this->getParameter('resave_to_noninterlaced') )
    {
      $temp_resource = imagecreatefromjpeg($value['tmp_name']);
      imageinterlace($temp_resource,0);
      imagejpeg($temp_resource,$value['tmp_name'],90);
      imagedestroy($temp_resource);
    }
    
    return true;
  }

  public function initialize($context, $parameters = null)
  {
    parent::initialize($context);

    $this->setParameter('min_width', null);
    $this->setParameter('min_width_error', 'File has a width too small');
    $this->setParameter('max_width', null);
    $this->setParameter('max_width_error', 'File has a width too big');
    $this->setParameter('min_height', null);
    $this->setParameter('min_height_error', 'File has a height too small');
    $this->setParameter('max_height', null);
    $this->setParameter('max_height_error', 'File has a height too big');
    $this->setParameter('exact_width', null);
    $this->setParameter('exact_width_error', 'File has an incorrect width');
    $this->setParameter('exact_height', null);
    $this->setParameter('exact_height_error', 'File has an incorrect height');
    $this->setParameter('aspect', null);
    $this->setParameter('aspect_error', 'File has an incorrect aspect ratio');
    $this->setParameter('resave_to_noninterlaced',false);
    
    $this->getParameterHolder()->add($parameters);

    return true;
  }
}
