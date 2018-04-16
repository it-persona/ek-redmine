<?php

namespace AppBundle\Controller;

use AppBundle\Form\TimeEntryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RedmineController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template(":default:index.html.twig")
     */
    public function indexAction(): array
    {
        return [];
    }

    /**
     * @Route("/projects", name="projects")
     * @Template(":redmine:projects.html.twig")
     */
    public function projectsAction(): array
    {
        $data = $this->container->get('app.bundle.redmine_helper')->getClient()->project->all();

        if (isset($data['projects'])) {
            foreach ($data['projects'] as $key => $project) {
                $issues = $this->container->get('app.bundle.redmine_helper')->getIssuesByProjectId($project['id']);
                $attr['total_count'] = (isset($issues['total_count']) ? $issues['total_count'] : 0);
                $data['projects'][$key] += $attr;
            }
        }

        return [
            'projects' => $data['projects']
        ];
    }

    /**
     * @Route("/issues", name="all-issues")
     * @Template(":redmine:all_issues.html.twig")
     */
    public function allIssuesAction(): array
    {
        $issues = $this->container->get('app.bundle.redmine_helper')->getClient()->issue->all();

        return [
            'issues'      => $issues['issues'],
            'total_count' => $issues['total_count']
        ];
    }

    /**
     * @Route("/project/{id}/issues", name="issues")
     * @Template(":redmine:issues.html.twig")
     */
    public function issuesAction($id): array
    {
        if (isset($id) && $id > 0) {
            $issues = $this->container->get('app.bundle.redmine_helper')->getIssuesByProjectId($id);
            $project = $this->container->get('app.bundle.redmine_helper')->getClient()->project->show($id);
        }

        return [
            'issues'  => (isset($issues)) ? $issues : null,
            'project' => (isset($project)) ? $project : null
        ];
    }

    /**
     * @Template(":redmine:new_activity.html.twig")
     * @Route("/activity/new", name="new-activity")
     * @Method(methods={"GET", "POST"})
     *
     * @param Request $request
     * @param array $data
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function newTimeEntryAction(Request $request, $data = [])
    {
        $query = $request->query->all();

        $data += ['activities' => $this->container->get('app.bundle.redmine_helper')->getTimeEntryActivities()];
        $data += ['projects' => $this->container->get('app.bundle.redmine_helper')->getProjects()];
        $data += ['issues' => $this->container->get('app.bundle.redmine_helper')->getIssues()];

        $form = $this->createForm(TimeEntryType::class, $data);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                $submit = $form->getData();
                $limitedComment = $this->container->get('app.bundle.redmine_helper')
                    ->textLimiter($submit['comment']);

                $this->container->get('app.bundle.redmine_helper')->getClient()->time_entry
                    ->create([
                        'project_id'  => $submit['project'],
                        'issue_id'    => $submit['issue'],
                        'hours'       => $submit['hours'],
                        'activity_id' => $submit['activity'],
                        'comments'    => $limitedComment,
                    ]);

                return $this->redirect($this->generateUrl('homepage'));
            }
        }

        return [
            'project_id' => ($query && array_key_exists('project_id', $query)) ? $query['project_id'] : '',
            'issue_id'   => ($query && array_key_exists('issue_id', $query)) ? $query['issue_id'] : '',
            'form'       => $form->createView(),
        ];
    }
}
