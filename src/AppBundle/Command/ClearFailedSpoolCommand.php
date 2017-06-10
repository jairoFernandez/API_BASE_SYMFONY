<?php
/**
 * User: jairo
 * Date: 7/01/17
 * Time: 07:10 PM
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ClearFailedSpoolCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('swiftmailer:spool:clear-failures')
            ->setDescription('Clears failures from the spool')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $transport \Swift_Transport */
        $transport = $this->getContainer()->get('swiftmailer.transport.real');
        $output->writeln("Begin process!");
        if (!$transport->isStarted()) {
            $transport->start();
        }
        $spoolPath = $this->getContainer()->getParameter('mailer_spool_path').'/default/';
        $output->writeln("Path: ".$spoolPath);
        $finder = Finder::create()->in($spoolPath)->name('*.sending');

        foreach ($finder as $failedFile) {

            // rename the file, so no other process tries to find it
            $tmpFilename = $failedFile . '.finalretry';
            $output->writeln("Finder begin...".$tmpFilename);
            rename($failedFile, $tmpFilename);

            /** @var $message \Swift_Message */
            $message = unserialize(file_get_contents($tmpFilename));
            $output->writeln(sprintf(
                'Retrying <info>%s</info> to <info>%s</info>',
                $message->getSubject(),
                implode(', ', array_keys($message->getTo()))
            ));

            try {
                $transport->send($message);
                $output->writeln('Sent!');
            } catch (\Swift_TransportException $e) {
                $output->writeln('<error>Send failed - deleting spooled message</error>');
            }

            // delete the file, either because it sent, or because it failed
            unlink($tmpFilename);
        }
    }
}