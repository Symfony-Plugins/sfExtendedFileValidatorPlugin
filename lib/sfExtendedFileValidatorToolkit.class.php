<?php
/*
 * This file is part of the sfExtendedFileValidatorPlugin package.
 *
 * (c) 2008 Vincent Lemaire <symfony@vincentlemaire.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class sfExtendedFileValidatorToolkit
{
  /**
   * Turns off image interlacement
   *
   * @param string Image path
   * @todo Check if the image is noninterlaced before resaving
   */
  public static function DisableImageInterlacement($img, $jpeg_quality = 90)
  {
    if (extension_loaded('gd'))
    {
      try
      {
        $resource = imagecreatefromjpeg($img);
        imageinterlace($resource, 0);
        imagejpeg($resource, $img, $jpeg_quality);
        imagedestroy($resource);
      }
      catch (Exception $e)
      {
        throw new Exception("Unable to create a noninterlaced image because ". $e->getMessage());
      }
    }
    else
    {
      throw new Exception("You must enable GD extension to disable interlacement.");
    }
  }
}
