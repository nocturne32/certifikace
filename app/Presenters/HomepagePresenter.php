<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Certification\PdfGenerator;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{

    /** @var PdfGenerator @inject */
    public $pdfGenerator;

    public function actionDefault(): void
    {
        $this->sendResponse($this->pdfGenerator->generateSignedCertificate([]));
    }

}
