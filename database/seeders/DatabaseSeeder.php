<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Question;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 管理员
        Admin::create([
            'phone' => '11012341234',
            'password' => bcrypt('123456'),
        ]);

        // 服务
        Service::create([
            'id' => 1,
            'title' => '智能咨询',
            'cover' => '/static/banners/chat.png',
            'type' => Service::TYPE_CHAT,
            'subtitle' => '智能法律助手如何与律师合作，推动法律专业发展迈向数字化未来',
            'content' => <<<EOF
在当今数字化时代，智能会话在法律问题解答中正迅速崛起，为律师和用户提供了全新的体验。这一新兴技术不仅在速度和效率上有显著优势，更在法律领域的准确性和深度分析方面展现出强大的潜力。

智能法律助手通过深度学习和自然语言处理技术，能够迅速解答用户的法律疑问。用户只需输入问题，系统即可以更接近对话的方式进行交互，提供实时的法律建议和解决方案。这种即时性对于用户来说是一种前所未有的便利，使得法律问题不再是复杂而费时的挑战。

然而，智能法律助手所体现的不仅仅是速度，更在于其在法律问题解答中的准确性。通过深度学习算法的支持，这些系统能够对海量的法律知识进行快速而准确的检索，为用户提供具有权威性的答案。这种准确性的提升不仅为个人用户提供了更加可靠的法律咨询，也为律师在专业领域中提供了有力的辅助工具。

与此同时，智能法律助手也引发了一系列的讨论，特别是在数据隐私方面。在提供全面法律解决方案的同时，系统需要处理大量敏感信息。因此，如何平衡用户隐私保护和法律问题解答的需求成为了智能法律助手发展中的一项重要挑战。相关法规和伦理准则的建立变得尤为关键，以确保智能会话的应用在法律领域中是安全可靠的。

法律智能的发展也为律师们带来了新的机遇。智能法律助手并非取代人类律师，而是与其合作，通过提供大量信息和数据分析支持，使得律师能够更专注于法律事务的深度解读和战略规划。这种协同合作不仅提高了工作效率，也为法律专业的发展带来了更加广阔的前景。

总的来说，智能会话在法律问题解答中正逐渐成为不可或缺的一部分。其高效、准确的特性为用户提供了更便捷的服务，同时为律师们提供了更多的专业支持。然而，随着技术的发展，我们也需要保障数据隐私、制定相应法规，并认真思考智能法律助手在法律体系中的角色，以实现其最大的潜力。在这个数字化的法律未来中，智能会话必将成为推动变革的力量，引领法律行业步入新的篇章。
EOF,
            'price' => 999,
        ]);

        Service::create([
            'id' => 2,
            'title' => '短视频IP打造',
            'cover' => '/static/banners/ip.png',
            'type' => Service::TYPE_IP,
            'subtitle' => '超越屏幕：将IP延伸到现实生活，创造更多元化的体验，让IP成为文化符号',
            'content' => <<<EOF
短视频IP的崛起标志着数字时代对创意和娱乐的全新定义。在这15秒的小小画布上，我们见证了无数创作者通过精心构建的IP，打破次元的壁垒，为观众呈现出一场场令人难以忘怀的视觉盛宴。

从创意到传播，短视频IP的成功并非偶然。创作者们将精力投入到每一个细节，借助独特的IP，成功引爆了短视频平台上的创意热潮。无论是诙谐幽默还是深刻思考，每一帧都是一个故事的开始，每一段视频都是一个IP传奇的开端。

在15秒内留下深刻印记，这似乎是一种魔法。然而，这正是IP打造的独特之处。创作者们深刻理解观众心理，通过每一帧的精心设计，使得短视频IP成为观众记忆中难以磨灭的瞬间。这种情感共鸣不受语言的限制，让观众在短短时间内建立起深厚的情感连接。

短视频时代，IP不仅仅是视频的主角，更是巨星的象征。借助精心打磨的IP，一些原本默默无闻的创作者在短视频领域崭露头角，成为引领潮流的数字时代巨星。这种崭新的娱乐方式使得每个人都有机会成为短视频领域的耀眼明星。

然而，IP打造并非只是数字娱乐的现象，它是一种创新的驱动力。创作者们不拘泥于传统，挖掘短视频IP的创新力，引领着整个行业的发展潮流。无论是新颖的拍摄技术还是独特的叙事结构，每一次创新都在重新定义着数字娱乐的未来。

在IP的生态圈中，我们看到了一个完整的系统正在形成。这个系统将短视频IP延伸到不同的平台，创造出更为丰富多彩的娱乐体验。IP的跨平台传播不仅让内容更为广泛地传播，也为创作者提供了更多的机会。

用户的参与是短视频IP成功的关键。通过IP打造，创作者们成功地激发了用户的参与欲望，打破了观众与内容之间的界限。用户不再只是被动地接受，而是成为了创意的共同创造者，共同构建着一个充满活力的数字社区。

短视频IP不仅仅存在于屏幕上，它超越了数字娱乐的范畴，延伸到了现实生活中。成功的IP不仅仅是数字内容，更是一种文化符号。它影响着人们的审美观念，引领着时尚潮流，成为一种社会文化的象征。

在技术创新的推动下，短视频IP不断演化成为数字艺术的代表。每一帧都是一幅画，每一个IP都是一件数字艺术品。通过技术的巧妙运用，短视频IP成功地将数字艺术融入到娱乐的方方面面，为观众带来了更为丰富的感官体验。

总体来说，短视频IP的打造是数字时代创意与娱乐的巅峰结晶。在这个15秒的舞台上，创作者们用心灵的火花点燃了创意的烈焰，让短视频不再只是瞬间的消遣，更成为了数字时代文化创新的先锋。通过IP的打造，我们看到了一个充满无限可能性的数字娱乐未来，一个由创作者和观众共同构筑的数字文化乐园。
EOF,
            'price' => 1888,
        ]);

        // 配置
        Setting::create([
            'title' => 'GPT配置',
            'content' => [
                'model' => 'gpt4',
                'preset' => config('gpt.preset_content'),
            ],
        ]);

        // 问题
        Question::create(['title' => '被裁员如何申请赔偿？']);
        Question::create(['title' => '工伤后未签订合同，如何申请赔偿']);
        Question::create(['title' => '公司断缴社保如何维权']);
        Question::create(['title' => '公司不给上社保是否违法']);
    }
}
