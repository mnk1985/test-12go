<?php

namespace App\Command;

use App\DTO\DestinationResults;
use App\Exception\BestPathExceptionInterface;
use App\Model\Destination;
use App\Service\BestPath\Calculator\DestinationCalculatorInterface;
use App\Service\BestPath\ParserInterface;
use App\Service\BestPath\ReaderInterface;
use App\Service\BestPath\WriterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateBestPaths extends Command
{
    const INPUT = 'inputFile';
    const OUTPUT = 'outputFile';
    /**
     * @var ParserInterface
     */
    private $parser;
    /**
     * @var ReaderInterface
     */
    private $reader;
    /**
     * @var DestinationCalculatorInterface
     */
    private $destinationCalculator;
    /**
     * @var WriterInterface
     */
    private $writer;

    public function __construct(
        string $name = null,
        ParserInterface $parser,
        ReaderInterface $reader,
        WriterInterface $writer,
        DestinationCalculatorInterface $destinationCalculator
    )
    {
        parent::__construct($name);
        $this->parser = $parser;
        $this->reader = $reader;
        $this->writer = $writer;
        $this->destinationCalculator = $destinationCalculator;
    }

    protected function configure()
    {
        $this
            // the name of the command
            ->setName('app:calculate-best-paths')
            // the short description shown while running "php bin/console list"
            ->setDescription('Calculates best paths')
            ->addArgument(self::INPUT, InputArgument::REQUIRED, 'full path to file with inputs')
            ->addArgument(self::OUTPUT, InputArgument::REQUIRED, 'full path to file for writing results in');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Input file: ',
            $input->getArgument(self::INPUT),
            '============',
        ]);

        $output->writeln([
            'Output file: ',
            $input->getArgument(self::OUTPUT),
            '============',
        ]);

        $content = $this->reader->read($input->getArgument(self::INPUT));

        if (!$content) {
            $output->writeln('<error>could not read input file</error>');
            return;
        }

        try {
            $destinations = $this->parser->run($content);
        } catch (BestPathExceptionInterface $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
            return;
        }

        $output->writeln('<info>Writing results to '.$input->getArgument(self::OUTPUT).'</info>');

        $destinationResults = new DestinationResults();

        /** @var Destination $destination */
        foreach ($destinations as $destination) {
            $destinationResult = $this->destinationCalculator->calculate($destination);

            $destinationResults->add($destinationResult);
        }

        $this->writer->write($input->getArgument(self::OUTPUT), $destinationResults);
    }
}