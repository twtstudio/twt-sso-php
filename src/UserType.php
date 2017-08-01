<?php

namespace TwT\SSO;

class UserType {
    const USER_PERSON = 1;
    const USER_ORGANIZATION = 2;
    const USER_PERSON_STUDENT = 11;
    const USER_PERSON_TEACHER = 12;
    const USER_ORGANIZATION_DEPARTMENT = 21;
    const USER_ORGANIZATION_CLUB = 22;

    const LIST = [
      1 => '博士生',
      2 => '硕士生',
      3 => '本科生',
      4 => '专科生',

      10 => '未知',
      11 => '教工',

      20 => '其他学校机构',
      21 => '学院',
      22 => '机关部处',
      23 => '直属单位',

      30 => '其他学生组织',
      31 => '校级学生组织',
      32 => '院级学生组织',
      33 => '校级职能类社团',
      34 => '校级兴趣类社团',
      35 => '院级社团'
    ];

    public static function check($type, $rule) {
        if (!isset(self::LIST[$type])) return false;

        switch ($rule) {
            case self::USER_PERSON: // 1 ~ 19
                return $type >= 1 && $type <= 19;
            case self::USER_ORGANIZATION: // 20 ~ 39
                return $type >= 20 && $type <= 39;
            case self::USER_PERSON_STUDENT: // 1 ~ 9
                return $type >= 1 && $type <= 9;
            case self::USER_PERSON_TEACHER: // 11 ~ 19
                return $type >= 11 && $type <= 19;
            case self::USER_ORGANIZATION_DEPARTMENT: // 20 ~ 29
                return $type >= 20 && $type <= 29;
            case self::USER_ORGANIZATION_CLUB: // 30 ~ 39
                return $type >= 30 && $type <= 39;
        }

        return false;
    }
}