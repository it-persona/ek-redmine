<?php

namespace AppBundle\Controller;

use Johnstyle\RedmineBundle\Service\RedmineClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RedmineController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template(":default:index.html.twig")
     */
    public function indexAction()
    {
        return null;
    }

    /**
     * @Route("/projects", name="projects")
     * @Template(":redmine:projects.html.twig")
     */
    public function projectsAction()
    {
        $data = $this->getClient()->project->all();

        return [
            'projects' => $data['projects']
        ];
    }

    /**
     * @Route("/project/{id}/issues", name="issues")
     * @Template(":redmine:issues.html.twig")
     */
    public function issuesAction($id)
    {
        $issues = $this->getIssuesByProjectId($id);

        return [
            'issues' => $issues
        ];
    }

    /**
     * @return \Redmine\Client
     */
    private function getClient()
    {
        return $this->container->get('johnstyle.redmine_client')->client();
    }

    private function getIssuesByProjectId($id)
    {
        if ($id) {
            $this->issues = $this->getClient()->issue->all(['project_id' => $id]);
        }

        return $this->issues;
    }
}
