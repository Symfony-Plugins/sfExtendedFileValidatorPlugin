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
    else
    {
      $imgs = $this->context->getRequest()->getFileNames();

      foreach ($imgs as $img)
      {
        $img = $this->context->getRequest()->getFilePath($img);

        /*
         * $imgData[0] retrieve width
         * $imgData[1] retrieve height
         */
        $imgData = @getimagesize($img['picture']);

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
      }

      return true;
    }
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

    $this->getParameterHolder()->add($parameters);

    return true;
  }
}
