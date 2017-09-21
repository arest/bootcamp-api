<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClientCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:oauth-server:client:create')
            ->setDescription('Creates a new client')
            ->addArgument('name', InputArgument::REQUIRED, 'Sets the client name', null)
            ->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets redirect uri for client. Use this option multiple times to set multiple redirect URIs.', null)
            ->addOption('grant-type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets allowed grant type for client. Use this option multiple times to set multiple grant types..', null)
            ->setHelp(<<<EOT
The <info>%command.name%</info>command creates a new client.

  <info>php %command.full_name% [--redirect-uri=...] [--grant-type=...] name</info>
   
EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setName($input->getArgument('name'));
        $client->setRedirectUris($input->getOption('redirect-uri'));
        $client->setAllowedGrantTypes($input->getOption('grant-type'));
        $clientManager->updateClient($client);
        $output->writeln(sprintf('Added a new client with name <info>%s</info> and public id <info>%s</info>.', $client->getName(), $client->getPublicId()));        
    }
}


    /*

        http://blog.logicexception.com/2012/04/securing-syfmony2-rest-service-wiith.html

        http://viddyoze.dev/app_dev.php/oauth/v2/auth?client_id=4_1cnhq8z5yxggkkco84csc00s4go8g4ggowcswgcwgws0848gss&response_type=code&redirect_uri=http%3A%2F%2Fwww.google.com

        http://viddyoze.dev/app_dev.php/oauth/v2/token?client_id=4_1cnhq8z5yxggkkco84csc00s4go8g4ggowcswgcwgws0848gss&client_secret=5tli1edtnoo4c84w04440o0s04g8skc880wgo0w0kgo0k4k0os&grant_type=authorization_code&redirect_uri=http://www.google.com&code=ZjI0MDY4YmU0NjMwZDlmMjFjY2Q1MTg4Y2Q4NGQyY2ZlZTVjNjZmZmU0YTc2OWM2NmYyYzBmYTdkODQxY2NkNA

        {
        "access_token": "N2NhOTU3Njg3OWIyYWMwYjJhNGFkOTZmY2U5MGZlZDg3ZDgyM2UwMTEyZDM4NWFiY2JkNzZmY2NmNjJjZDE1ZQ",
        "expires_in": 3600,
        "token_type": "bearer",
        "scope": null,
        "refresh_token": "MDMwZDk5ZWZkZGQxZTliMWJlOTcxMWExMzU4ZDAxN2IwY2YyODMwNGQzOTNlMmY3OTRjNGU3YzY3ZGMyNzc3MA"
        }


        EXTRA. Implicit Grant Flow
        http://viddyoze.dev/app_dev.php/oauth/v2/auth?client_id=4_1cnhq8z5yxggkkco84csc00s4go8g4ggowcswgcwgws0848gss&redirect_uri=http%3A%2F%2Fwww.google.com&response_type=token

        http://google.com?access_token=NDU5NDE4ZWMxZDQ1NzQ2OWY2ZjJiMTQyODIzNWJhMWM0YTFmZmQxZTA5OTAwMGVkOTk4ZmU0NGY2ZjkzZjJkNw&expires_in=3600&token_type=bearer

    */