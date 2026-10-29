<?php

declare(strict_types=1);

namespace SBW\WDB\Controller;

use Psr\Http\Message\ResponseInterface;
use SBW\WDB\Domain\Model\Project;
use SBW\WDB\Domain\Model\ProjectStatus;
use SBW\WDB\Domain\Repository\ProjectRepository;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

/**
 * Controller for the projects (second plugin "ProjectIndex", on its own page).
 */
class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    public function indexAction(): ResponseInterface
    {
        $this->view->assign('projects', $this->projectRepository->findAll());

        return $this->htmlResponse();
    }

    /**
     * Validation is ignored so that every saved project can be shown. If the project does not exist, TYPO3
     * shows a 404 page (see plugin.tx_wdb.mvc.* in the TypoScript).
     */
    #[IgnoreValidation(['argumentName' => 'project'])]
    public function showAction(Project $project): ResponseInterface
    {
        $this->view->assign('project', $project);

        return $this->htmlResponse();
    }

    /**
     * The project argument is only filled when the form is shown again after a failed validation.
     */
    #[IgnoreValidation(['argumentName' => 'project'])]
    public function newAction(?Project $project = null): ResponseInterface
    {
        $this->assignFormData($project);

        return $this->htmlResponse();
    }

    public function initializeCreateAction(): void
    {
        $this->useHtmlDateFormat();
    }

    public function createAction(Project $project): ResponseInterface
    {
        $this->projectRepository->add($project);
        $this->addFlashMessage($this->translate('frontend.flash.project.created', [$project->getName()]));

        return $this->redirectToAction('index');
    }

    #[IgnoreValidation(['argumentName' => 'project'])]
    public function editAction(Project $project): ResponseInterface
    {
        $this->assignFormData($project);

        return $this->htmlResponse();
    }

    public function initializeUpdateAction(): void
    {
        $this->useHtmlDateFormat();
    }

    public function updateAction(Project $project): ResponseInterface
    {
        $this->projectRepository->update($project);
        $this->addFlashMessage($this->translate('frontend.flash.project.updated', [$project->getName()]));

        return $this->redirectToAction('index');
    }

    /**
     * Only reachable via POST. The TCA has a "deleted" field, so the project is only marked as deleted
     * and can be restored in the backend.
     */
    #[IgnoreValidation(['argumentName' => 'project'])]
    public function deleteAction(Project $project): ResponseInterface
    {
        $name = $project->getName();
        $this->projectRepository->remove($project);
        $this->addFlashMessage($this->translate('frontend.flash.project.deleted', [$name]));

        return $this->redirectToAction('index');
    }

    private function assignFormData(?Project $project): void
    {
        $statusOptions = [];
        foreach (ProjectStatus::formOptions() as $status) {
            $statusOptions[$status] = $this->translate('tx_wdb_domain_model_project.status.' . $status);
        }

        $this->view->assignMultiple([
            'project' => $project,
            'statusOptions' => $statusOptions,
        ]);
    }

    private function useHtmlDateFormat(): void
    {
        $configuration = $this->arguments->getArgument('project')->getPropertyMappingConfiguration();
        foreach (['startDate', 'endDate'] as $property) {
            $configuration->forProperty($property)->setTypeConverterOption(
                DateTimeConverter::class,
                DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                '!Y-m-d'
            );
        }
    }
}
