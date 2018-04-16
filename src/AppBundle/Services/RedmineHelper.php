<?php

namespace AppBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class RedmineHelper
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var array
     */
    private $issues;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return \Redmine\Client
     */
    public function getClient(): \Redmine\Client
    {
        return $this->container->get('johnstyle.redmine_client')->client();
    }

    /**
     * @param $id
     * @return array
     */
    public function getIssuesByProjectId($id): array
    {
        if ($id) {
            $this->issues = $this->getClient()->issue->all(['project_id' => $id]);
        }

        return $this->issues;
    }

    /**
     * @param $id
     * @return array
     */
    public function getIssuesTotalCount($id): array
    {
        if (isset($data)) {
            $data = $this->getIssuesByProjectId($id);
        }

        return [
            'total_count' => $data['total_count']
        ];
    }

    /**
     * @param array $stack
     * @return array
     */
    public function getProjects($stack = []): array
    {
        $projects = $this->getClient()->project->all();

        foreach ($projects['projects'] as $project) {
            $stack += [$project['name'] => $project['id']];
        }

        return $stack;
    }

    /**
     * @param array $stack
     * @return array
     */
    public function getIssues($stack = []): array
    {
        $issues = $this->getClient()->issue->all();

        foreach ($issues['issues'] as $issue) {
            $stack += [$issue['subject'] => $issue['id']];
        }

        return $stack;
    }

    /**
     * @param array $stack
     * @return array
     */
    public function getTimeEntryActivities($stack = []): array
    {
        $activities = $this->getClient()->time_entry_activity->all();

        foreach ($activities['time_entry_activities'] as $activity) {
            $stack += [$activity['name'] => $activity['id']];
        }

        return $stack;
    }
}