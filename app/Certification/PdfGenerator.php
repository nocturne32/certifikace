<?php


namespace App\Certification;


use Joseki\Application\Responses\PdfResponse;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use RuntimeException;

class PdfGenerator
{
    public const DEFAULT_DIR = __DIR__ . '/../../temp/pdf';

    private const CERTIFICATES_DIR = 'certificates';

    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    /** @var array */
    private $dirs = [self::CERTIFICATES_DIR];

    public function __construct(TemplateFactory $templateFactory)
    {
        $this->templateFactory = $templateFactory;

        foreach ($this->dirs as $dir) {
            $dirPath = self::DEFAULT_DIR . '/' . $dir;
            if (!file_exists($dirPath) && !mkdir($dirPath, 0755, true) && !is_dir($dirPath)) {
                throw new RuntimeException(sprintf("'%s' directory ('%s') was not created", ucfirst($dir), $dirPath));
            }
        }
    }

    public function generateSignedCertificate(array $params): PdfResponse
    {
        $template = $this->templateFactory->create();

        $template->setFile(__DIR__ . '/templates/pdf_certificate_signed.latte');
        $template->setParameters($params);

        $pdf = new PdfResponse($template);
        $documentTitle = ($params['title'] ?? 'Certifikát');

        $pdf->setDocumentTitle($documentTitle);

        return $pdf;
    }

    public function savePdfResponseFile(PdfResponse $pdfResponse): string
    {
        return $pdfResponse->save(self::DEFAULT_DIR . '/'. self::CERTIFICATES_DIR, $pdfResponse->getDocumentTitle() . '.pdf');
    }

}

