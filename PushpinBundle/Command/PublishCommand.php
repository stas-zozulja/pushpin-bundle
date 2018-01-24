<?php

namespace Gamma\Pushpin\PushpinBundle\Command;

use Gamma\Pushpin\PushpinBundle\Messages\GammaHttpStreamMessage;
use Gamma\Pushpin\PushpinBundle\Messages\GammaWebSocketMessage;
use Gamma\Pushpin\PushpinBundle\Messages\PublishableInterface;
use Gamma\Pushpin\PushpinBundle\Services\MessagePublisherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PublishCommand extends Command
{
    const ARGUMENT_FORMAT = 'format';
    const ARGUMENT_CHANNEL = 'channel';
    const ARGUMENT_PARAMETERS = 'params';

    const FORMATS_MAP = [
        GammaWebSocketMessage::FORMAT => GammaWebSocketMessage::class,
        GammaHttpStreamMessage::FORMAT => GammaHttpStreamMessage::class,
    ];

    protected static $defaultName = 'pushpin:publish';

    /**
     * @var MessagePublisherInterface
     */
    private $publisher;

    /**
     * PublishCommand constructor.
     *
     * @param MessagePublisherInterface $publisher
     */
    public function __construct(MessagePublisherInterface $publisher)
    {
        $this->publisher = $publisher;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition([
                new InputArgument(self::ARGUMENT_FORMAT, InputArgument::REQUIRED, 'A format of message'),
                new InputArgument(self::ARGUMENT_CHANNEL, InputArgument::REQUIRED, 'A channel name to publish message'),
                new InputArgument(self::ARGUMENT_PARAMETERS, InputArgument::IS_ARRAY, 'Data to build a message of specified format'),
            ])
            ->setDescription('Publishes a message to configured Pushpin instance')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $format = $input->getArgument(self::ARGUMENT_FORMAT);
        $channel = $input->getArgument(self::ARGUMENT_CHANNEL);
        $message = $this->createMessage($format, $input->getArgument(self::ARGUMENT_PARAMETERS));

        $io->title('Publishing message');
        $io->listing([
            sprintf('channel "%s"', $channel),
            sprintf('format "%s"', $message->name()),
            sprintf('content "%s"', $message->content),
        ]);

        try {
            $this->publisher->publish($channel, $message);
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return 1;
        }

        $io->success('Message successfully published.');

        return 0;
    }

    /**
     * @param string $format
     * @param array  $parameters
     *
     * @return PublishableInterface
     */
    private function createMessage(string $format, array $parameters): PublishableInterface
    {
        $class = self::FORMATS_MAP[$format] ?? null;

        if (null === $class) {
            throw new \RuntimeException(sprintf('Format "%s" is unknown.', $format));
        }

        $message = call_user_func_array([$class, 'build'], $parameters);

        return $message;
    }
}
